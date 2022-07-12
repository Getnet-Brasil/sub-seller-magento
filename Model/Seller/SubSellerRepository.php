<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Model\Seller;

use Getnet\SubSellerMagento\Api\Data;
use Getnet\SubSellerMagento\Api\Data\SubSellerInterface;
use Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface;
use Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller\Collection;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SubSellerRepository implements SubSellerRepositoryInterface
{
    public const MESSAGE_SUB_SELLER_ID_IS_NOT_ALLOWED = 'id is not expected for this request.';

    /**
     * @var SubSellerRegistry
     */
    protected $subSellerRegistry;

    /**
     * @var SubSellerFactory
     */
    protected $subSellerFactory;

    /**
     * @var Data\SubSellerSearchResultsInterfaceFactory
     */
    private $subSellerSearchResultsFactory;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $regionFactory;

    /**
     * @var \Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller
     */
    protected $resourceModel;

    /**
     * @var JoinProcessorInterface
     */
    protected $joinProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param SubSellerRegistry                                             $subSellerRegistry
     * @param Data\SubSellerSearchResultsInterfaceFactory                   $subSellerSearchResultsFactory
     * @param SubSellerFactory                                              $subSellerFactory
     * @param CountryFactory                                                $countryFactory
     * @param RegionFactory                                                 $regionFactory
     * @param \Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller $subSellerResource
     * @param JoinProcessorInterface                                        $joinProcessor
     * @param CollectionProcessorInterface|null                             $collectionProcessor
     */
    public function __construct(
        SubSellerRegistry $subSellerRegistry,
        Data\SubSellerSearchResultsInterfaceFactory $subSellerSearchResultsFactory,
        SubSellerFactory $subSellerFactory,
        CountryFactory $countryFactory,
        RegionFactory $regionFactory,
        \Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller $subSellerResource,
        JoinProcessorInterface $joinProcessor,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->subSellerRegistry = $subSellerRegistry;
        $this->subSellerSearchResultsFactory = $subSellerSearchResultsFactory;
        $this->subSellerFactory = $subSellerFactory;
        $this->countryFactory = $countryFactory;
        $this->regionFactory = $regionFactory;
        $this->resourceModel = $subSellerResource;
        $this->joinProcessor = $joinProcessor;
        $this->collectionProcessor = $collectionProcessor
            ?? ObjectManager::getInstance()->get(
                // phpcs:ignore Magento2.PHP.LiteralNamespaces
                'Getnet\SubSellerMagento\Model\Api\SearchCriteria\SubSellerCollectionProcessor'
            );
    }

    /**
     * Save.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller
     *
     * @return Data\SubSellerInterface
     */
    public function save(\Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller)
    {
        if ($subSeller->getId()) {
            $this->subSellerRegistry->retrieveSubSeller($subSeller->getId());
        }
        $this->validate($subSeller);

        try {
            $this->resourceModel->save($subSeller);
        } catch (LocalizedException $e) {
            throw $e;
        }
        $this->subSellerRegistry->registerSubSeller($subSeller);

        return $subSeller;
    }

    /**
     * @inheritdoc
     */
    public function get($subSellerId)
    {
        return $this->subSellerRegistry->retrieveSubSeller($subSellerId);
    }

    /**
     * @inheritdoc
     */
    public function delete(\Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller)
    {
        return $this->resourceModel->delete($subSeller);
    }

    /**
     * @inheritdoc
     */
    public function deleteById($subSellerId)
    {
        $subSellerModel = $this->subSellerRegistry->retrieveSubSeller($subSellerId);
        $this->delete($subSellerModel);
        $this->subSellerRegistry->removeSubSeller($subSellerId);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller\Collection $collection */
        $collection = $this->subSellerFactory->create()->getCollection();
        // $this->joinProcessor->process($collection);
        // $collection->joinRegionTable();

        $this->collectionProcessor->process($searchCriteria, $collection);
        $subSeller = [];

        /** @var \Getnet\SubSellerMagento\Model\Seller\SubSeller $subSellerModel */
        foreach ($collection as $subSellerModel) {
            $subSeller[] = $subSellerModel;
        }

        return $this->subSellerSearchResultsFactory->create()
            ->setItems($subSeller)
            ->setTotalCount($collection->getSize())
            ->setSearchCriteria($searchCriteria);
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection  $collection
     *
     * @return void
     *
     * @deprecated 100.2.0
     */
    protected function addFilterGroupToCollection(FilterGroup $filterGroup, Collection $collection)
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $this->translateField($filter->getField());
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * Translates a field name to a DB column name for use in collection queries.
     *
     * @deprecated 100.2.0
     *
     * @param string $field a field name that should be translated to a DB column name.
     *
     * @return string
     */
    protected function translateField($field)
    {
        switch ($field) {
            case SubSeller::KEY_REGION_NAME:
                return 'region_table.code';
            default:
                return 'main_table.'.$field;
        }
    }

    /**
     * Validate sub seller.
     *
     * @param SubSellerInterface $subSeller
     *
     * @throws InputException
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function validate($subSeller)
    {
        $exception = new InputException();
        
        if ($subSeller->getId() === 0) {
            $subSeller->setId(null);
        }

        if (!\Zend_Validate::is($subSeller->getLegalDocumentNumber(), 'NotEmpty')) {
            $exception->addError(
                __(
                    '"%fieldName" is required. Enter and try again.',
                    [
                        'fieldName' => 'legal_documment_number',
                    ]
                )
            );
        }
        if ($subSeller->getLegalDocumentNumber()) {
            $legalDocumentNumber = preg_replace('/[^0-9]/', '', $subSeller->getLegalDocumentNumber());

            if (strlen($legalDocumentNumber) === 11) {
                $subSeller->setType(0);
            }

            if (strlen($legalDocumentNumber) === 14) {
                $subSeller->setType(1);
            }
        }

        if ($exception->wasErrorAdded()) {
            throw $exception;
        }
    }
}
