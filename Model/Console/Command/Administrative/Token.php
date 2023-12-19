<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Model\Console\Command\Administrative;

use Exception;
use Getnet\SubSellerMagento\Helper\Data as GetnetHelper;
use Getnet\SubSellerMagento\Logger\Logger;
use Getnet\SubSellerMagento\Model\Config as GetnetConfig;
use Getnet\SubSellerMagento\Model\Console\Command\AbstractModel;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use Laminas\Http\ClientFactory;
use Laminas\Http\Request;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Generate Token.
 */
class Token extends AbstractModel
{
    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @var Pool
     */
    protected $cacheFrontendPool;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var GetnetConfig
     */
    protected $getnetConfig;

    /**
     * @var GetnetHelper
     */
    protected $getnetHelper;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var ClientFactory
     */
    protected $httpClientFactory;

    /**
     * @param TypeListInterface    $cacheTypeList
     * @param Pool                 $cacheFrontendPool
     * @param Logger               $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param State                $state
     * @param GetnetConfig         $getnetConfig
     * @param GetnetHelper         $getnetHelper
     * @param Config               $config
     * @param Json                 $json
     * @param ClientFactory        $httpClientFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        State $state,
        GetnetConfig $getnetConfig,
        GetnetHelper $getnetHelper,
        Config $config,
        Json $json,
        ClientFactory $httpClientFactory
    ) {
        parent::__construct(
            $logger
        );
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->state = $state;
        $this->scopeConfig = $scopeConfig;
        $this->getnetConfig = $getnetConfig;
        $this->config = $config;
        $this->json = $json;
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * Command Preference.
     *
     * @param int|null $storeId
     *
     * @return void
     */
    public function newToken($storeId = null)
    {
        $storeId = $storeId ?: 0;
        $this->writeln('Generate Administrative Token');
        $this->createNewToken($storeId);
        $this->writeln(__('Finished'));
    }

    /**
     * Create New Token.
     *
     * @param int $storeId
     *
     * @return void
     */
    private function createNewToken($storeId)
    {
        $newToken = $this->getNewToken();
        if ($newToken['success']) {
            $token = $newToken['response'];
            if (isset($token['access_token'])) {
                $registryConfig = $this->setNewToken($token['access_token'], $storeId);
                if ($registryConfig['success']) {
                    $this->cacheTypeList->cleanType('config');

                    // phpcs:ignore Generic.Files.LineLength
                    $this->writeln('<info>'.__('Token Refresh Successfully.').'</info>');

                    return;
                }
                // phpcs:ignore Generic.Files.LineLength
                $this->writeln('<error>'.__('Error saving information in database: %1', $registryConfig['error']).'</error>');
            }
            // phpcs:ignore Generic.Files.LineLength
            $this->writeln('<error>'.__('Refresh Token Error: %1', $token['error_description']).'</error>');

            return;
        }
        $this->writeln('<error>'.__('Token update request error: %1', $newToken['error']).'</error>');
    }

    /**
     * Get New Token.
     *
     * @return array
     */
    private function getNewToken(): array
    {
        $uri = $this->getnetConfig->getUri();
        $clientId = $this->getnetConfig->getMerchantGatewayClientId();
        $clientSecret = $this->getnetConfig->getMerchantGatewayClientSecret();
        $dataSend = [
            'scope'      => 'mgm',
            'grant_type' => 'client_credentials',
        ];

        $client = $this->httpClientFactory->create();
        $client->setUri($uri.'credenciamento/auth/oauth/v2/token');
        $client->setAuth($clientId, $clientSecret);
        $client->setOptions(['maxredirects' => 0, 'timeout' => 30]);
        $client->setHeaders(['content' => 'application/x-www-form-urlencoded']);
        $client->setParameterPost($dataSend);
        $client->setMethod(Request::METHOD_POST);

        try {
            $result = $client->send()->getBody();
            $response = $this->json->unserialize($result);
            $this->logger->info($this->json->serialize([
                'uri' => $uri.'credenciamento/auth/oauth/v2/token',
                'response' => $response
            ]));

            return [
                'success'    => true,
                'response'   => $response,
            ];
        } catch (Exception $exc) {
            $this->logger->info($this->json->serialize([
                'uri' => $uri.'credenciamento/auth/oauth/v2/token',
                'response' => $client->send()->getBody(),
                'error' => $exc->getMessage()
            ]));

            return ['success' => false, 'error' =>  $exc->getMessage()];
        }
    }

    /**
     * Set New Token.
     *
     * @param string $token
     * @param int    $storeId
     *
     * @return array
     */
    private function setNewToken(string $token, int $storeId): array
    {
        $environment = $this->getnetConfig->getEnvironment();
        $pathPattern = 'getnet_sub_seller/general/credentials/%s_%s';
        $pathConfigId = sprintf($pathPattern, 'access_token', $environment);

        try {
            $this->config->saveConfig(
                $pathConfigId,
                $token,
                'default',
                $storeId
            );
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }

        return ['success' => true];
    }
}
