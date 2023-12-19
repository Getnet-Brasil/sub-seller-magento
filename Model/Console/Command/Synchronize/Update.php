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
use Laminas\Http\ClientFactory;
use Laminas\Http\Request;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Update Sub Seller on Getnet.
 */
class Update extends AbstractModel
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
     * @var ClientFactory
     */
    protected $httpClientFactory;

    /**
     * @param State                        $state
     * @param Logger                       $logger
     * @param GetnetConfig                 $getnetConfig
     * @param GetnetHelper                 $getnetHelper
     * @param SubSellerRepositoryInterface $subSellerRepository
     * @param Json                         $json
     * @param ClientFactory                $httpClientFactory
     */
    public function __construct(
        State $state,
        Logger $logger,
        GetnetConfig $getnetConfig,
        GetnetHelper $getnetHelper,
        SubSellerRepositoryInterface $subSellerRepository,
        Json $json,
        ClientFactory $httpClientFactory
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
    public function update(int $subSellerId)
    {
        $this->writeln('Init Sync Sub Seller');
        $this->updateSubSeller($subSellerId);
        $this->writeln(__('Finished'));
    }

    /**
     * Update Sub Seller.
     *
     * @param int $subSellerId
     *
     * @return void
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function updateSubSeller(int $subSellerId)
    {
        try {
            $subSeller = $this->subSellerRepository->get($subSellerId);
        } catch (LocalizedException $exc) {
            $this->writeln('<error>'.$exc->getMessage().'</error>');

            return;
        }

        if ($subSeller->getId()) {
            $typePersona = (bool) $subSeller->getType();
            if (!$subSeller->getIdExt()) {
                $messageErrors = __('The Sub Seller does not have a relationship id on Getnet');
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));
            }

            $messageInfo = __('Send Sub Seller internal code: %1', $subSeller->getCode());
            $this->writeln(sprintf('<info>%s</info>', $messageInfo));
            $formatedData = $this->getnetHelper->formatedData($subSeller, $typePersona, 'seller_update');
            $this->writeln($this->json->serialize($formatedData));
            $response = $this->updateData($formatedData, $typePersona, (int) $subSeller->getStatus());

            if ($response->getErrors()) {
                foreach ($response->getErrors() as $error) {
                    $errors[] = $error;
                }
                $listErrors = implode(', ', $errors);
                $messageErrors = __('Could not update registration, errors found: %1', $listErrors);
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));

                return;
            }

            if ($response->getMessage()) {
                $errors[] = $response->getMessage();
                if (is_array($response->getMessage())) {
                    unset($errors);
                    foreach ($response->getMessage() as $key => $error) {
                        foreach ($error as $typeError) {
                            $errors[] = $typeError.' field '.$key;
                        }
                    }
                }
                $listErrors = implode(', ', $errors);
                $messageErrors = __('Could not update registration, errors found: %1', $listErrors);
                $this->writeln(sprintf('<error>%s</error>', $messageErrors));

                return;
            }

            if ($response->getSuccess()) {
                $this->writeln('<info>Done!</info>');

                return;
            }

            if ($response->getSubsellerId()) {
                $messageResponse = __(
                    'Success, the sub seller id: %1, enable to sales: %2, id getnet: %3',
                    $response->getSubsellerId(),
                    $response->getEnabled()
                );
                $this->writeln(sprintf('<info>%s</info>', $messageResponse));

                return;
            }

            $messageErrorConection = __('Connection Error: %1', json_encode($response));
            $this->writeln(sprintf('<error>%s</error>', $messageErrorConection));

            return;
        }
        $this->writeln('<error>'.__('Error').'</error>');
    }

    /**
     * Update Data.
     *
     * @param array $sellerFomarted
     * @param bool  $typePersona
     * @param int   $status
     *
     * @return \Magento\Framework\DataObject
     */
    protected function updateData(
        array $sellerFomarted,
        bool $typePersona,
        int $status
    ): \Magento\Framework\DataObject {
        $uri = $this->getnetConfig->getUri();
        $bearer = $this->getnetConfig->getAuth();
        $client = $this->httpClientFactory->create();
        $send = $this->json->serialize($sellerFomarted);
        $client->setUri($uri.'v1/mgm/pf/update-subseller');
        $headers = [
            'Authorization'               => 'Bearer '.$bearer,
            'Content-Type'                => 'application/json'
        ];

        if ($typePersona) {
            $client->setUri($uri.'v1/mgm/pj/update-subseller');
        }

        if ($status === 5) {
            $client->setUri($uri.'v1/mgm/pf/complement');

            if ($typePersona) {
                $client->setUri($uri.'v1/mgm/pj/complement');
            }
        }

        $client->setHeaders($headers);
        $client->setOptions(['maxredirects' => 0, 'timeout' => 30]);
        
        $client->setRawBody($send);
        $client->setMethod(Request::METHOD_PUT);
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
                $getnetData->setMessage($response['Message']);
            }

            if (isset($response['success'])) {
                $getnetData->setSuccess(true);

                return $getnetData;
            }

            $getnetData->setData($result);

            return $getnetData;
        } catch (Exception $e) {
            $this->logger->info($this->json->serialize(['error' => $e->getMessage()]));

            return $getnetData->setDetails($e->getMessage());
        }
    }
}
