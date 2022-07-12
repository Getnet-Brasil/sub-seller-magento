<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Model\Console\Command\Synchronize;

use Exception;
use Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface;
use Getnet\SubSellerMagento\Helper\Data as GetnetHelper;
use Getnet\SubSellerMagento\Logger\Logger;
use Getnet\SubSellerMagento\Model\Config as GetnetConfig;
use Getnet\SubSellerMagento\Model\Console\Command\AbstractModel;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Data Sub Seller on Getnet.
 */
class GetUpdateData extends AbstractModel
{
    /**
     * @var State
     */
    protected $state;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var GetnetConfig
     */
    protected $getnetConfig;

    /**
     * @var GetnetHelper
     */
    protected $getnetHelper;

    /**
     * @var SubSellerRepositoryInterface
     */
    protected $subSellerRepository;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var ZendClientFactory
     */
    protected $httpClientFactory;

    /**
     * @param State                        $state
     * @param Logger                       $logger
     * @param GetnetConfig                 $getnetConfig
     * @param GetnetHelper                 $getnetHelper
     * @param SubSellerRepositoryInterface $subSellerRepository
     * @param Json                         $json
     * @param ZendClientFactory            $httpClientFactory
     */
    public function __construct(
        State $state,
        Logger $logger,
        GetnetConfig $getnetConfig,
        GetnetHelper $getnetHelper,
        SubSellerRepositoryInterface $subSellerRepository,
        Json $json,
        ZendClientFactory $httpClientFactory
    ) {
        $this->state = $state;
        $this->logger = $logger;
        $this->getnetConfig = $getnetConfig;
        $this->getnetHelper = $getnetHelper;
        $this->subSellerRepository = $subSellerRepository;
        $this->json = $json;
        $this->httpClientFactory = $httpClientFactory;
    }

    /**
     * Command Preference.
     *
     * @param int $subSellerId
     *
     * @return void
     */
    public function getUpdateData(int $subSellerId)
    {
        $this->writeln('Init Sync Data Sub Seller');
        $this->updateDataSubSeller($subSellerId);
        $this->writeln(__('Finished'));
    }

    /**
     * Create Update Data.
     *
     * @param int $subSellerId
     *
     * @return void
     */
    protected function updateDataSubSeller(int $subSellerId)
    {
        try {
            $subSeller = $this->subSellerRepository->get($subSellerId);
        } catch (LocalizedException $exc) {
            $this->writeln('<error>'.$exc->getMessage().'</error>');

            return;
        }

        if ($subSeller->getId()) {
            if (!$subSeller->getIdExt()) {
                $messageErrors = __('The Sub Seller does not have a relationship id on Getnet.');
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));
            }

            $typePersona = (bool) $subSeller->getType();
            $legalDocument = preg_replace('/[^0-9]/', '', $subSeller->getLegalDocumentNumber());

            $messageInfo = __('Get Data to Sub Seller internal code: %1', $subSeller->getCode());
            $this->writeln(sprintf('<info>%s</info>', $messageInfo));

            $response = $this->getDataOnGetnet(
                $typePersona,
                $legalDocument,
                $subSeller->getMerchantId()
            );

            if ($response->getSubsellerId()) {
                if ($response->getEnabled() === 'S') {
                    $subSeller->setIdExt($response->getSubsellerId());
                    $subSeller->setStatus(3);
                    $subSeller->setStatusComment(null);
                    $subSeller->save();
                }

                $messageResponse = __(
                    'Success, sub seller id: %1, enable to sale: %2',
                    $response->getSubsellerId(),
                    $response->getEnabled()
                );
                $this->writeln(sprintf('<info>%s</info>', $messageResponse));

                return;
            }

            $messageErrorConection = __('Error: %1', $response->getDetails());
            $this->writeln(sprintf('<error>%s</error>', $messageErrorConection));

            return;
        }
        $this->writeln('<error>'.__('Error').'</error>');
    }

    /**
     * Get Data On Getnet.
     *
     * @param bool   $typePersona
     * @param string $legalNumber
     * @param string $merchantId
     *
     * @return \Magento\Framework\DataObject
     */
    protected function getDataOnGetnet(
        bool $typePersona,
        string $legalNumber,
        string $merchantId
    ): \Magento\Framework\DataObject {
        $client = $this->httpClientFactory->create();
        $uriBase = $this->getnetConfig->getUri();
        $bearer = $this->getnetConfig->getAuth();
        $type = 'pf';

        if ($typePersona) {
            $type = 'pj';
        }

        $uri = sprintf('%sv1/mgm/%s/callback/%s/%s', $uriBase, $type, $merchantId, $legalNumber);

        $client->setUri($uri);
        $client->setHeaders('Authorization', 'Bearer '.$bearer);
        $client->setMethod(ZendClient::GET);
        $getnetData = new \Magento\Framework\DataObject();

        try {
            $result = $client->request()->getBody();
            $response = $this->json->unserialize($result);
            $getnetData->setData($response);

            return $getnetData;
        } catch (Exception $e) {
            $this->logger->info($this->json->serialize(['error' => $e->getMessage()]));

            return $getnetData->setDetails($e->getMessage());
        }
    }
}
