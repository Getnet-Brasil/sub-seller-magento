<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Api\Data;

/**
 * Interface for sub seller addresses results.
 *
 * @api
 *
 * @since 100.0.2
 */
interface AddressesInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Get sub seller business address street.
     *
     * @return string|null
     */
    public function getAddressStreet();

    /**
     * Set sub seller business address street.
     *
     * @param string $addressStreet
     *
     * @return $this
     */
    public function setAddressStreet($addressStreet);

    /**
     * Get sub seller business address street number.
     *
     * @return string|null
     */
    public function getAddressStreetNumber();

    /**
     * Set sub seller business address street number.
     *
     * @param string $addressStreetNumber
     *
     * @return $this
     */
    public function setAddressStreetNumber($addressStreetNumber);

    /**
     * Get sub seller business address street district.
     *
     * @return string|null
     */
    public function getAddressStreetDistrict();

    /**
     * Set sub seller business address street district.
     *
     * @param string $addressStreetDistrict
     *
     * @return $this
     */
    public function setAddressStreetDistrict($addressStreetDistrict);

    /**
     * Get sub seller business address street complement.
     *
     * @return string|null
     */
    public function getAddressStreetComplement();

    /**
     * Set sub seller business address street complement.
     *
     * @param string $addressStreetComplement
     *
     * @return $this
     */
    public function setAddressStreetComplement($addressStreetComplement);

    /**
     * Get sub seller business address city.
     *
     * @return string|null
     */
    public function getAddressCity();

    /**
     * Set sub seller business address city.
     *
     * @param string $addressCity
     *
     * @return $this
     */
    public function setAddressCity($addressCity);

    /**
     * Get sub seller business address region.
     *
     * @return string|null
     */
    public function getAddressRegion();

    /**
     * Set sub seller business address region.
     *
     * @param string $addressRegion
     *
     * @return $this
     */
    public function setAddressRegion($addressRegion);

    /**
     * Get sub seller business address region id.
     *
     * @return int|null
     */
    public function getAddressRegionId();

    /**
     * Set sub seller business address region id.
     *
     * @param int $addressRegionId
     *
     * @return $this
     */
    public function setAddressRegionId($addressRegionId);

    /**
     * Get sub seller business address postcode.
     *
     * @return string|null
     */
    public function getAddressPostcode();

    /**
     * Set sub seller business address postcode.
     *
     * @param string $addressPostcode
     *
     * @return $this
     */
    public function setAddressPostcode($addressPostcode);

    /**
     * Get sub seller business address country id.
     *
     * @return string|null
     */
    public function getAddressCountryId();

    /**
     * Set sub seller business address country id.
     *
     * @param string $addressCountryId
     *
     * @return $this
     */
    public function setAddressCountryId($addressCountryId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\AddressesExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\AddressesExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Getnet\SubSellerMagento\Api\Data\AddressesExtensionInterface $extensionAttributes
    );
}
