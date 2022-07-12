<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Cron;

use Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface;
use Getnet\SubSellerMagento\Logger\Logger;
use Getnet\SubSellerMagento\Model\Console\Command\Synchronize\GetUpdateData;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Synchronize Get Update Sub Seller on Getnet.
 */
class SynchronizeGetUpdate
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var GetUpdateData
     */
    protected $getUpdateData;

    /**
     * @var SubSellerRepositoryInterface
     */
    protected $subSellerRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteria;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * Constructor.
     *
     * @param Logger                       $logger
     * @param GetUpdateData                $getUpdateData
     * @param SubSellerRepositoryInterface $subSellerRepository
     * @param SearchCriteriaBuilder        $searchCriteria
     * @param FilterBuilder                $filterBuilder
     */
    public function __construct(
        Logger $logger,
        GetUpdateData $getUpdateData,
        SubSellerRepositoryInterface $subSellerRepository,
        SearchCriteriaBuilder $searchCriteria,
        FilterBuilder $filterBuilder
    ) {
        $this->logger = $logger;
        $this->getUpdateData = $getUpdateData;
        $this->subSellerRepository = $subSellerRepository;
        $this->searchCriteria = $searchCriteria;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Execute the cron.
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info('Cronjob Sync Get Upate Sub Seller');
        $searchCriteria = $this->searchCriteria->addFilters(
            [
                $this->filterBuilder->setField('status')->setValue(2)->create(),
                $this->filterBuilder->setField('status')->setValue(4)->create(),
                $this->filterBuilder->setField('status')->setValue(5)->create(),
            ]
        )->create();
        $subSellers = $this->subSellerRepository->getList($searchCriteria);

        foreach ($subSellers->getItems() as $subSeller) {
            $this->logger->info(sprintf('Cronjob Sync Get Upate Sub Seller id %s.', $subSeller->getId()));
            $this->getUpdateData->getUpdateData($subSeller->getId());
        }

        $this->logger->info('Cronjob Sync Get Upate Sub Seller is done.');
    }
}
