<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Model\Seller;

use Getnet\SubSellerMagento\Model\Seller\SubSeller as SubSellerModel;
use Getnet\SubSellerMagento\Model\Seller\SubSellerFactory as SubSellerModelFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class SubSellerRegistry
{
    /**
     * @var SubSellerModelFactory
     */
    private $subSellerModelFactory;

    /**
     * @var SubSellerModel[]
     */
    private $subSellerRegistryById = [];

    /**
     * Constructor.
     *
     * @param SubSellerModelFactory $sellerModelSubSellerFactory
     */
    public function __construct(
        SubSellerModelFactory $sellerModelSubSellerFactory
    ) {
        $this->subSellerModelFactory = $sellerModelSubSellerFactory;
    }

    /**
     * Register SubSeller Model to registry.
     *
     * @param SubSellerModel $subSellerModel
     *
     * @return void
     */
    public function registerSubSeller(SubSellerModel $subSellerModel)
    {
        $this->subSellerRegistryById[$subSellerModel->getId()] = $subSellerModel;
    }

    /**
     * Retrieve SubSeller Model from registry given an id.
     *
     * @param int $subSellerId
     *
     * @throws NoSuchEntityException
     *
     * @return SubSellerModel
     */
    public function retrieveSubSeller($subSellerId)
    {
        if (isset($this->subSellerRegistryById[$subSellerId])) {
            return $this->subSellerRegistryById[$subSellerId];
        }
        /** @var SubSellerModel $subSellerModel */
        $subSellerModel = $this->subSellerModelFactory->create()->load($subSellerId);
        if (!$subSellerModel->getId()) {
            // sub seller does not exist
            throw NoSuchEntityException::singleField('subSellerId', $subSellerId);
        }
        $this->subSellerRegistryById[$subSellerModel->getId()] = $subSellerModel;

        return $subSellerModel;
    }

    /**
     * Remove an instance of the SubSeller Model from the registry.
     *
     * @param int $subSellerId
     *
     * @return void
     */
    public function removeSubSeller($subSellerId)
    {
        unset($this->subSellerRegistryById[$subSellerId]);
    }
}
