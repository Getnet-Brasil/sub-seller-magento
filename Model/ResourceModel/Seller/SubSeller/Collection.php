<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Sub Seller collection.
 */

namespace Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller;

use Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller as SubSellerResourceModel;
use Getnet\SubSellerMagento\Model\Seller\SubSeller;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Collection of SubSeller.
 */
class Collection extends AbstractCollection
{
    /**
     * Value of fetched from DB of rules per cycle.
     */
    public const SUB_SELLER_CHUNK_SIZE = 1000;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param EntityFactory          $entityFactory
     * @param LoggerInterface        $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface       $eventManager
     * @param StoreManagerInterface  $storeManager
     * @param mixed                  $connection
     * @param AbstractDb             $resource
     */
    public function __construct(
        EntityFactory $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }

    /**
     * Resource initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(SubSeller::class, SubSellerResourceModel::class);
    }

    /**
     * Add subSeller filter.
     *
     * @param int $subSellerId
     *
     * @return $this
     */
    public function addSubSellerFilter($subSellerId)
    {
        if (is_int($subSellerId) && $subSellerId > 0) {
            return $this->addFieldToFilter('main_table.getnet_sub_seller_id', $subSellerId);
        }

        return $this;
    }

    /**
     * Retrieve option array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('getnet_sub_seller_id', 'code');
    }

    /**
     * Retrieve option hash.
     *
     * @return array
     */
    public function toOptionHash()
    {
        return $this->_toOptionHash('getnet_sub_seller_id', 'code');
    }

    /**
     * Convert items array to hash for select options using fetchItem method.
     *
     * @return array
     *
     * @see fetchItem()
     */
    public function toOptionHashOptimized()
    {
        $result = [];
        while ($item = $this->fetchItem()) {
            $result[$item->getData('getnet_sub_seller_id')] = $item->getData('code');
        }

        return $result;
    }

    /**
     * Get subSeller array without memory leak.
     *
     * @return array
     */
    public function getOptionSubSeller()
    {
        $size = self::SUB_SELLER_CHUNK_SIZE;
        $page = 1;
        $subSeller = [];
        do {
            $offset = $size * ($page - 1);
            $this->getSelect()->reset();
            $this->getSelect()
                ->from(
                    ['subSeller' => $this->getMainTable()],
                    ['getnet_sub_seller_id', 'code']
                )
                ->limit($size, $offset);

            $subSeller[] = $this->toOptionArray();
            $this->clear();
            $page++;
        } while ($this->getSize() > $offset);

        return array_merge([], ...$subSeller);
    }
}
