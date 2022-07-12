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
 * Class Create Sub Seller on Getnet.
 */
class Create extends AbstractModel
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
    public function create(int $subSellerId)
    {
        $this->writeln('Init Sync Sub Seller');
        $this->createSubSeller($subSellerId);
        $this->writeln(__('Finished'));
    }

    /**
     * Create Sub Seller.
     *
     * @param int $subSellerId
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * phpcs:disable Generic.Metrics.NestingLevel
     */
    protected function createSubSeller(int $subSellerId)
    {
        try {
            $subSeller = $this->subSellerRepository->get($subSellerId);
        } catch (LocalizedException $exc) {
            $this->writeln('<error>'.$exc->getMessage().'</error>');

            return;
        }

        if ($subSeller->getId()) {
            $typePersona = (bool) $subSeller->getType();
            if ($subSeller->getIdExt()) {
                $messageErrors = __('O Sub Seller já possuí id de relacionamento na Getnet');
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));

                return;
            }

            $formatedData = $this->getnetHelper->formatedData($subSeller, $typePersona);
            $messageInfo = __('Send Sub Seller internal code: %1', $subSeller->getCode());
            $this->writeln(sprintf('<info>%s</info>', $messageInfo));
            $this->writeln($this->json->serialize($formatedData));
            $response = $this->sendData($formatedData, $typePersona);

            if ($response->getErrors()) {
                foreach ($response->getErrors() as $error) {
                    $errors[] = $error;
                }
                $listErrors = implode(', ', $errors);

                $subSeller->setStatus('4');
                $subSeller->setStatusComment($listErrors);
                $subSeller->save();
                $messageErrors = __('Unable to register, errors found: %1', $listErrors);
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));

                return;
            }

            if ($response->getMessage()) {
                $errors[] = $response->getMessage();
                if (is_array($response->getMessage())) {
                    unset($errors);
                    foreach ($response->getMessage() as $key => $error) {
                        $errors[] = $error;
                        if (is_array($error)) {
                            unset($errors);
                            foreach ($error as $typeError) {
                                $errors[] = $typeError.' field '.$key;
                            }
                        }
                    }
                }
                $listErrors = implode(', ', $errors);
                $subSeller->setStatus('5');
                $subSeller->setStatusComment($listErrors);
                $subSeller->save();
                $messageErrors = __('Unable to register, errors found: %1', $listErrors);
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));

                return;
            }

            if ($response->getSubsellerId()) {
                $subSeller->setIdExt($response->getSubsellerId());
                $subSeller->setStatus('2');
                $subSeller->setStatusComment('Waiting for registration approval.');

                if ($response->getEnabled() === 'S') {
                    $subSeller->setStatus('3');
                    $subSeller->setStatusComment('Authorized for sales.');
                }

                $subSeller->save();
                $messageResponse = __(
                    'Success, the sub seller id: %1, enable to sales: %2, id getnet: %3',
                    $response->getCode(),
                    $response->getEnabled(),
                    $response->getSubsellerId()
                );
                $this->writeln(sprintf('<info>%s</info>', $messageResponse));

                return;
            }

            $messageErrorConection = __('Connection Error: %1', $response->getDetails());
            $this->writeln(sprintf('<error>%s</error>', $messageErrorConection));

            return;
        }
    }

    /**
     * Send Data.
     *
     * @param array $sellerFomarted
     * @param bool  $typePersona
     *
     * @return \Magento\Framework\DataObject
     */
    protected function sendData(
        array $sellerFomarted,
        bool $typePersona
    ): \Magento\Framework\DataObject {
        $uri = $this->getnetConfig->getUri();
        $bearer = $this->getnetConfig->getAuth();
        $client = $this->httpClientFactory->create();
        $send = $this->json->serialize($sellerFomarted);
        $client->setUri($uri.'v1/mgm/pf/create-presubseller');
        if ($typePersona) {
            $client->setUri($uri.'v1/mgm/pj/create-presubseller');
        }
        $client->setHeaders('Authorization', 'Bearer '.$bearer);
        $client->setConfig(['maxredirects' => 0, 'timeout' => 40]);
        $client->setRawData($send, 'application/json');
        $client->setMethod(ZendClient::POST);
        $getnetData = new \Magento\Framework\DataObject();

        try {
            $result = $client->request()->getBody();
            $response = $this->json->unserialize($result);
            $this->logger->info(
                $this->json->serialize([
                    'send'     => $send,
                    'response' => $response,
                ])
            );

            if (isset($response['status_code'])) {
                return $getnetData->setDetails($response['details']);
            }

            if (isset($response['Message'])) {
                if (isset($response['ModelState'])) {
                    $getnetData->setMessage($response['ModelState']);

                    return $getnetData;
                }
                $getnetData->setMessage($response);

                return $getnetData;
            }

            $getnetData->setData($response);

            return $getnetData;
        } catch (Exception $e) {
            $this->logger->info($this->json->serialize(['error' => $e->getMessage()]));

            return $getnetData->setDetails($e->getMessage());
        }
    }
}
