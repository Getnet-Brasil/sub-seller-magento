<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status - Defines sub seller gender.
 */
class Status implements ArrayInterface
{
    /**
     * Returns Options.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            1 => __('Pending'),
            2 => __('Registered, Pending Authorization'),
            3 => __('Authorized Sub Seller'),
            4 => __('Not Authorized'),
            5 => __('Requires Registration Update'),
        ];
    }
}
