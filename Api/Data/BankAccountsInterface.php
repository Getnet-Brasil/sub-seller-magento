<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Api\Data;

/**
 * Interface for sub seller bank accounts results.
 *
 * @api
 *
 * @since 100.0.2
 */
interface BankAccountsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Get sub seller Account Type. Format C for Checking, P for Saving.
     *
     * @return string|null
     */
    public function getAccountType();

    /**
     * Set sub seller Account Type. Format C for Checking, P for Saving.
     *
     * @param string $accountType
     *
     * @return $this
     */
    public function setAccountType($accountType);

    /**
     * Get sub seller Bank.
     *
     * @return string|null
     */
    public function getBank();

    /**
     * Set sub seller Bank.
     *
     * @param string $bank
     *
     * @return $this
     */
    public function setBank($bank);

    /**
     * Get sub seller agency.
     *
     * @return string|null
     */
    public function getAgency();

    /**
     * Set sub seller Agency.
     *
     * @param string $agency
     *
     * @return $this
     */
    public function setAgency($agency);

    /**
     * Get sub seller agency digit.
     *
     * @return string|null
     */
    public function getAgencyDigit();

    /**
     * Set sub seller Agency Digit.
     *
     * @param string $agencyDigit
     *
     * @return $this
     */
    public function setAgencyDigit($agencyDigit);

    /**
     * Get sub seller Account.
     *
     * @return string|null
     */
    public function getAccount();

    /**
     * Set sub seller Account.
     *
     * @param string $account
     *
     * @return $this
     */
    public function setAccount($account);

    /**
     * Get sub seller Account Digit.
     *
     * @return string|null
     */
    public function getAccountDigit();

    /**
     * Set sub seller Account Digit.
     *
     * @param string $accountDigit
     *
     * @return $this
     */
    public function setAccountDigit($accountDigit);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\BankAccountsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\BankAccountsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Getnet\SubSellerMagento\Api\Data\BankAccountsExtensionInterface $extensionAttributes
    );
}
