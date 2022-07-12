<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Environment - Defines environment types.
 */
class Environment implements ArrayInterface
{
    /**
     * Returns Options.
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            'production' => __('Production'),
            'homolog'    => __('Homolog'),
            'sandbox'    => __('Sandbox - Environment for tests'),
        ];
    }
}
