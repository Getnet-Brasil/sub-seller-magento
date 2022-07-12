<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Sub Seller factory.
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */

namespace Getnet\SubSellerMagento\Model\Seller;

class SubSellerFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create new sub seller model.
     *
     * @param array $arguments
     *
     * @return \Getnet\SubSellerMagento\Model\Seller\SubSeller
     */
    public function create(array $arguments = [])
    {
        return $this->_objectManager->create(\Getnet\SubSellerMagento\Model\Seller\SubSeller::class, $arguments);
    }
}
