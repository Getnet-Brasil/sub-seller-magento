<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Sub Seller resource model.
 */

namespace Getnet\SubSellerMagento\Model\ResourceModel\Seller;

class SubSeller extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Resource initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_setMainTable('getnet_sub_seller');
    }

    /**
     * Initialize unique fields.
     *
     * @return $this
     */
    protected function _initUniqueFields()
    {
        $this->_uniqueFields = [
            [
                'field' => ['legal_document_number'],
                'title' => __('Legal Document Number'),
            ],
            [
                'field' => ['code'],
                'title' => __('Code'),
            ],
        ];

        return $this;
    }
}
