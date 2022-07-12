<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Model\Seller;

use Getnet\SubSellerMagento\Helper\Data as SubSellerHelper;

/**
 * Sub Seller Model Addresses.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Addresses extends \Magento\Framework\Model\AbstractExtensibleModel implements
    \Getnet\SubSellerMagento\Api\Data\AddressesInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */
    public const ADDRESS_STREET = 'address_street';
    public const ADDRESS_STREET_NUMBER = 'address_street_number';
    public const ADDRESS_STREET_DISTRICT = 'address_street_district';
    public const ADDRESS_STREET_COMPLEMENT = 'address_street_complement';
    public const ADDRESS_CITY = 'address_city';
    public const ADDRESS_REGION = 'address_region';
    public const ADDRESS_REGION_ID = 'address_region_id';
    public const ADDRESS_POSTCODE = 'address_postcode';
    public const ADDRESS_COUNTRY_ID = 'address_country_id';
    /**#@-*/

    /**
     * @var SubSellerHelper
     */
    protected $subSellerHelper;

    /**
     * @param SubSellerHelper $subSellerHelper
     */
    public function __construct(
        SubSellerHelper $subSellerHelper
    ) {
        $this->subSellerHelper = $subSellerHelper;
    }

    /**
     * Get Sub Seller business address street number.
     *
     * @return string;
     */
    public function getAddressStreet()
    {
        return $this->getData(self::ADDRESS_STREET);
    }

    /**
     * Set Sub Seller business address street number.
     *
     * @param string $addressStreet
     *
     * @return $this
     */
    public function setAddressStreet($addressStreet)
    {
        return $this->setData(self::ADDRESS_STREET, $addressStreet);
    }

    /**
     * Get Sub Seller business address street number.
     *
     * @return string;
     */
    public function getAddressStreetNumber()
    {
        return $this->getData(self::ADDRESS_STREET_NUMBER);
    }

    /**
     * Set Sub Seller business address street number.
     *
     * @param string $addressStreetNumber
     *
     * @return $this
     */
    public function setAddressStreetNumber($addressStreetNumber)
    {
        return $this->setData(self::ADDRESS_STREET_NUMBER, $addressStreetNumber);
    }

    /**
     * Get Sub Seller business address street district.
     *
     * @return string;
     */
    public function getAddressStreetDistrict()
    {
        return $this->getData(self::ADDRESS_STREET_DISTRICT);
    }

    /**
     * Set Sub Seller business address street district.
     *
     * @param string $addressStreetDistrict
     *
     * @return $this
     */
    public function setAddressStreetDistrict($addressStreetDistrict)
    {
        return $this->setData(self::ADDRESS_STREET_DISTRICT, $addressStreetDistrict);
    }

    /**
     * Get Sub Seller business address street district.
     *
     * @return string;
     */
    public function getAddressStreetComplement()
    {
        return $this->getData(self::ADDRESS_STREET_COMPLEMENT);
    }

    /**
     * Set Sub Seller business address street district.
     *
     * @param string $addressStreetComplement
     *
     * @return $this
     */
    public function setAddressStreetComplement($addressStreetComplement)
    {
        return $this->setData(self::ADDRESS_STREET_COMPLEMENT, $addressStreetComplement);
    }

    /**
     * Get Sub Seller var.
     *
     * @return string;
     */
    public function getAddressCity()
    {
        return $this->getData(self::ADDRESS_CITY);
    }

    /**
     * Set Sub Seller business address city.
     *
     * @param string $addressCity
     *
     * @return $this
     */
    public function setAddressCity($addressCity)
    {
        return $this->setData(self::ADDRESS_CITY, $addressCity);
    }

    /**
     * Get Sub Seller business address region.
     *
     * @return string;
     */
    public function getAddressRegion()
    {
        return $this->getData(self::ADDRESS_REGION);
    }

    /**
     * Set Sub Seller business address region.
     *
     * @param string $addressRegion
     *
     * @return $this
     */
    public function setAddressRegion($addressRegion)
    {
        return $this->setData(self::ADDRESS_REGION, $addressRegion);
    }

    /**
     * Set Geb Seller business address region id.
     *
     * @return int
     */
    public function getAddressRegionId()
    {
        return $this->getData(self::ADDRESS_REGION_ID);
    }

    /**
     * Set Sub Seller business address region id.
     *
     * @param string $addressRegionId
     *
     * @return $this
     */
    public function setAddressRegionId($addressRegionId)
    {
        return $this->setData(self::ADDRESS_REGION_ID, $addressRegionId);
    }

    /**
     * Set Sub Seller business address postcode.
     *
     * @param string $addressPostcode
     *
     * @return $this
     */
    public function setAddressPostcode($addressPostcode)
    {
        return $this->setData(self::ADDRESS_POSTCODE, $addressPostcode);
    }

    /**
     * Get Sub Seller business address postcode.
     *
     * @return string;
     */
    public function getAddressPostcode()
    {
        return $this->getData(self::ADDRESS_POSTCODE);
    }

    /**
     * Get Sub Seller business address country id.
     *
     * @return string;
     */
    public function getAddressCountryId()
    {
        return $this->getData(self::ADDRESS_COUNTRY_ID);
    }

    /**
     * Set Sub Seller business address country id.
     *
     * @param string $addressCountryId
     *
     * @return $this
     */
    public function setAddressCountryId($addressCountryId)
    {
        return $this->setData(self::ADDRESS_COUNTRY_ID, $addressCountryId);
    }

    /**
     * @inheritdoc
     *
     * @return \Getnet\SubSellerMagento\Api\Data\AddressesExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     *
     * @param \Getnet\SubSellerMagento\Api\Data\AddressesExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Getnet\SubSellerMagento\Api\Data\AddressesExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
