<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Model;

use Getnet\SubSellerMagento\Api\Data\SubSellerInterface as SubSeller;
use Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface;
use Magento\Framework\Api\AbstractServiceCollection;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Data\Collection\EntityFactory;

/**
 * Sub Seller collection for a grid backed by Services.
 */
class SubSellerCollection extends AbstractServiceCollection
{
    /**
     * @var SubSellerRepositoryInterface
     */
    protected $subSellerRepository;

    /**
     * Initialize dependencies.
     *
     * @param EntityFactory                $entityFactory
     * @param FilterBuilder                $filterBuilder
     * @param SearchCriteriaBuilder        $searchCriteriaBuilder
     * @param SortOrderBuilder             $sortOrderBuilder
     * @param SubSellerRepositoryInterface $subSellerService
     */
    public function __construct(
        EntityFactory $entityFactory,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        SubSellerRepositoryInterface $subSellerService
    ) {
        parent::__construct($entityFactory, $filterBuilder, $searchCriteriaBuilder, $sortOrderBuilder);
        $this->subSellerRepository = $subSellerService;
    }

    /**
     * @inheritdoc
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $searchCriteria = $this->getSearchCriteria();
            $searchResults = $this->subSellerRepository->getList($searchCriteria);
            $this->_totalRecords = $searchResults->getTotalCount();
            foreach ($searchResults->getItems() as $subSeller) {
                $this->_addItem($this->createSubSellerCollectionItem($subSeller));
            }
            $this->_setIsLoaded();
        }

        return $this;
    }

    /**
     * Creates a subseller's collection item.
     *
     * @param SubSeller $subSeller Input data for creating the item.
     *
     * @return \Magento\Framework\DataObject Collection item a sub seller
     */
    protected function createSubSellerCollectionItem(SubSeller $subSeller)
    {
        $collectionItem = new \Magento\Framework\DataObject();
        $collectionItem->setSubSellerId($subSeller->getId());
        $collectionItem->setMerchantId($subSeller->getMerchantId());
        $collectionItem->setCode($subSeller->getCode());
        $collectionItem->setIdExt($subSeller->getIdExt());
        $collectionItem->setEmail($subSeller->getEmail());
        $collectionItem->setLegalDocumentNumber($subSeller->getLegalDocumentNumber());
        $collectionItem->setType($subSeller->getType());
        $collectionItem->setLegalName($subSeller->getLegalName());
        $collectionItem->setBirthDate($subSeller->getBirthDate());
        $collectionItem->setAddresses($subSeller->getAddresses());
        $collectionItem->setTelephone($subSeller->getTelephone());
        $collectionItem->setBankAccounts($subSeller->getBankAccounts());
        $collectionItem->setPaymentPlan($subSeller->getPaymentPlan());
        $collectionItem->setAcceptedContract($subSeller->getAcceptedContract());
        $collectionItem->setMarketplaceStore($subSeller->getMarketplaceStore());
        $collectionItem->setOccupation($subSeller->getOccupation());
        $collectionItem->setMonthlyGrossIncome($subSeller->getMonthlyGrossIncome());
        $collectionItem->setGrossEquity($subSeller->getGrossEquity());
        $collectionItem->setTrade($subSeller->getTrade());
        $collectionItem->setStatus($subSeller->getStatus());
        $collectionItem->setStatusComment($subSeller->getStatusComment());

        return $collectionItem;
    }
}
