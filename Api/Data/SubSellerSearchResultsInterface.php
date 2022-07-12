<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for sub seller search results.
 *
 * @api
 *
 * @since 100.0.2
 */
interface SubSellerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerInterface[]
     */
    public function getItems();

    /**
     * Set items.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);
}
