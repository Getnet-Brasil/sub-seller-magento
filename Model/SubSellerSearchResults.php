<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Model;

use Getnet\SubSellerMagento\Api\Data\SubSellerSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Service Data Object with Sub Seller search results.
 */
class SubSellerSearchResults extends SearchResults implements SubSellerSearchResultsInterface
{
}
