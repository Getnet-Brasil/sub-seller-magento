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
 * Sub Seller Model Bank Accounts.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BankAccounts extends \Magento\Framework\Model\AbstractExtensibleModel implements
    \Getnet\SubSellerMagento\Api\Data\BankAccountsInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */
    public const ACCOUNT_TYPE = 'account_type';
    public const BANK = 'bank';
    public const AGENCY = 'agency';
    public const AGENCY_DIGIT = 'agency_digit';
    public const ACCOUNT = 'account';
    public const ACCOUNT_DIGIT = 'account_digit';
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
     * Get Sub Seller Account Type.
     *
     * @return string;
     */
    public function getAccountType()
    {
        return $this->getData(self::ACCOUNT_TYPE);
    }

    /**
     * Set Sub Seller Account Type.
     *
     * @param string $accountType
     *
     * @return $this
     */
    public function setAccountType($accountType)
    {
        return $this->setData(self::ACCOUNT_TYPE, $accountType);
    }

    /**
     * Get Sub Seller Bank.
     *
     * @return string;
     */
    public function getBank()
    {
        return $this->getData(self::BANK);
    }

    /**
     * Set Sub Seller Bank.
     *
     * @param string $bank
     *
     * @return $this
     */
    public function setBank($bank)
    {
        return $this->setData(self::BANK, $bank);
    }

    /**
     * Get Sub Seller Agency.
     *
     * @return string;
     */
    public function getAgency()
    {
        return $this->getData(self::AGENCY);
    }

    /**
     * Set Sub Seller Agency.
     *
     * @param string $agency
     *
     * @return $this
     */
    public function setAgency($agency)
    {
        return $this->setData(self::AGENCY, $agency);
    }

    /**
     * Get Sub Seller Agency Digit.
     *
     * @return string;
     */
    public function getAgencyDigit()
    {
        return $this->getData(self::AGENCY_DIGIT);
    }

    /**
     * Set Sub Seller Agency Digit.
     *
     * @param string $agencyDigit
     *
     * @return $this
     */
    public function setAgencyDigit($agencyDigit)
    {
        return $this->setData(self::AGENCY_DIGIT, $agencyDigit);
    }

    /**
     * Get Sub Seller Account.
     *
     * @return string;
     */
    public function getAccount()
    {
        return $this->getData(self::ACCOUNT);
    }

    /**
     * Set Sub Seller Account.
     *
     * @param string $account
     *
     * @return $this
     */
    public function setAccount($account)
    {
        return $this->setData(self::ACCOUNT, $account);
    }

    /**
     * Get Sub Seller Digit Account.
     *
     * @return string;
     */
    public function getAccountDigit()
    {
        return $this->getData(self::ACCOUNT_DIGIT);
    }

    /**
     * Set Sub Seller Account Digit.
     *
     * @param string $accountDigit
     *
     * @return $this
     */
    public function setAccountDigit($accountDigit)
    {
        return $this->setData(self::ACCOUNT_DIGIT, $accountDigit);
    }

    /**
     * @inheritdoc
     *
     * @return \Getnet\SubSellerMagento\Api\Data\BankAccountsExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     *
     * @param \Getnet\SubSellerMagento\Api\Data\BankAccountsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Getnet\SubSellerMagento\Api\Data\BankAccountsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
