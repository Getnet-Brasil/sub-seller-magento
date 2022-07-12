<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Api;

/**
 * Sub Seller CRUD interface.
 *
 * @api
 *
 * @since 100.0.2
 */
interface SubSellerRepositoryInterface
{
    /**
     * Create or update sub seller.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller
     *
     * @throws \Magento\Framework\Exception\InputException If input is invalid or required input is missing.
     * @throws \Exception                                  If something went wrong while creating the SubSeller.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerInterface
     */
    public function save(\Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller);

    /**
     * Get sub seller.
     *
     * @param int $subSellerId
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerInterface
     */
    public function get($subSellerId);

    /**
     * Delete sub seller.
     *
     * @param int $subSellerId
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException If no SubSeller with the given ID can be found.
     * @throws \Exception                                         If something went wrong while performing the delete.
     *
     * @return bool
     */
    public function deleteById($subSellerId);

    /**
     * Search SubSellers.
     *
     * This call returns an array of objects, but detailed information about each object’s attributes might not be
     * included. See https://devdocs.magento.com/codelinks/attributes.html#SubSellerRepositoryInterface to
     * determine which call to use to get detailed information about all attributes for an object.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *                                                                       phpcs:disable Generic.Files.LineLength
     *
     * @throws \Magento\Framework\Exception\InputException If there is a problem with the input
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerSearchResultsInterface containing Data\SubSellerInterface objects
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete sub seller.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException If no SubSeller with the given ID can be found.
     * @throws \Exception                                         If something went wrong while performing the delete.
     *
     * @return bool
     */
    public function delete(\Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller);
}
