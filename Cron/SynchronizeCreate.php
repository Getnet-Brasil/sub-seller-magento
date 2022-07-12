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
use Getnet\SubSellerMagento\Model\Console\Command\Synchronize\Create;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Synchronize Create Sub Seller on Getnet.
 */
class SynchronizeCreate
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Create
     */
    protected $create;

    /**
     * @var SubSellerRepositoryInterface
     */
    protected $subSellerRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteria;

    /**
     * Constructor.
     *
     * @param Logger                       $logger
     * @param Create                       $create
     * @param SubSellerRepositoryInterface $subSellerRepository
     * @param SearchCriteriaBuilder        $searchCriteria
     */
    public function __construct(
        Logger $logger,
        Create $create,
        SubSellerRepositoryInterface $subSellerRepository,
        SearchCriteriaBuilder $searchCriteria
    ) {
        $this->logger = $logger;
        $this->create = $create;
        $this->subSellerRepository = $subSellerRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * Execute the cron.
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->info('Cronjob Sync Create Seller');

        $searchCriteria = $this->searchCriteria->addFilter('status', 1)->create();
        $subSellers = $this->subSellerRepository->getList($searchCriteria);

        foreach ($subSellers->getItems() as $subSeller) {
            $this->logger->info(sprintf('Cronjob Sync Create Seller id %s.', $subSeller->getId()));
            $this->create->create($subSeller->getId());
        }

        $this->logger->info('Cronjob Sync Create Seller is done.');
    }
}
