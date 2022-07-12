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
 * Class BankTypeAccounts - Defines bank type accounts.
 */
class BankTypeAccounts implements ArrayInterface
{
    /**
     * Returns Options.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            'unique'   => __('Unique'),
        ];
    }
}
