<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Admin Source Sub Seller.
 */
declare(strict_types=1);

namespace Getnet\SubSellerMagento\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class BankTypeAccount - Defines bank type accounts.
 */
class BankTypeAccount implements ArrayInterface
{
    /**
     * Returns Options.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            'C' => __('Checking'),
            'P' => __('Saving'),
        ];
    }
}
