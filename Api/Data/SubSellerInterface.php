<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Api\Data;

/**
 * Interface for sub seller search results.
 *
 * @api
 *
 * @since 100.0.2
 */
interface SubSellerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id);

    /**
     * Get account merchant id.
     *
     * @return string|null
     */
    public function getMerchantId();

    /**
     * Set account merchant id.
     *
     * @param string $merchantId
     *
     * @return $this
     */
    public function setMerchantId($merchantId);

    /**
     * Get sub seller load data by customer id.
     *
     * @return string|null
     */
    public function getLoadDataByCustomerId();

    /**
     * Set sub seller load data by customer id.
     *
     * @param int $customerId
     *
     * @return $this
     */
    public function setLoadDataByCustomerId($customerId);

    /**
     * Get sub seller internal code.
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Set sub seller internal code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code);

    /**
     * Get sub seller id on Getnet.
     *
     * @return string|null
     */
    public function getIdExt();

    /**
     * Set sub seller id on Getnet.
     *
     * @param string $idExt
     *
     * @return $this
     */
    public function setIdExt($idExt);

    /**
     * Get sub seller email.
     *
     * @return string|null
     */
    public function getEmail();

    /**
     * Set sub seller email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email);

    /**
     * Get sub seller id legal document number (CPF/CNPJ).
     *
     * @return string|null
     */
    public function getLegalDocumentNumber();

    /**
     * Set sub seller id legal document number (CPF/CNPJ).
     *
     * @param string $legalDocumentNumber
     *
     * @return $this
     */
    public function setLegalDocumentNumber($legalDocumentNumber);

    /**
     * Get sub seller type (PF = 0, PJ = 1).
     *
     * @return int|null
     */
    public function getType();

    /**
     * Set sub seller type (PF = 0, PJ = 1).
     *
     * @param int $type
     *
     * @return $this
     */
    public function setType($type);

    /**
     * Get sub seller legal name.
     *
     * @return string|null
     */
    public function getLegalName();

    /**
     * Set sub seller type.
     *
     * @param string $legalName
     *
     * @return $this
     */
    public function setLegalName($legalName);

    /**
     * Get sub seller birth date.
     *
     * @return string|null
     */
    public function getBirthDate();

    /**
     * Set sub seller type.
     *
     * @param string $birthDate
     *
     * @return $this
     */
    public function setBirthDate($birthDate);

    /**
     * Set the list of addresses.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\AddressesInterface[] $addresses
     *
     * @return $this
     */
    public function setAddresses(array $addresses = null);

    /**
     * Get the list of addresses.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\AddressesInterface[]|null
     */
    public function getAddresses();

    /**
     * Get sub seller telephone.
     *
     * @return string|null
     */
    public function getTelephone();

    /**
     * Set sub seller telephone.
     *
     * @param string $telephone
     *
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * Get the list sub seller bank accounts.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\BankAccountsInterface[]|null $bankAccounts
     */
    public function getBankAccounts();

    /**
     * Set the list sub seller bank accounts.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\BankAccountsInterface[] $bankAccounts
     *
     * @return $this
     */
    public function setBankAccounts(array $bankAccounts = null);

    /**
     * Get sub seller payment plan.
     *
     * @return int|null
     */
    public function getPaymentPlan();

    /**
     * Set sub seller payment plan.
     *
     * @param int $paymentPlan
     *
     * @return $this
     */
    public function setPaymentPlan($paymentPlan);

    /**
     * Get sub seller accepted contract.
     *
     * @return bool|null
     */
    public function getAcceptedContract();

    /**
     * Set sub seller accepted contract.
     *
     * @param bool $acceptedContract
     *
     * @return $this
     */
    public function setAcceptedContract($acceptedContract);

    /**
     * Get sub seller marketplace store.
     *
     * @return bool|null
     */
    public function getMarketplaceStore();

    /**
     * Set sub seller marketplace store.
     *
     * @param bool $marketplaceStore
     *
     * @return $this
     */
    public function setMarketplaceStore($marketplaceStore);

    /**
     * Get sub seller occupation.
     *
     * @return string|null
     */
    public function getOccupation();

    /**
     * Set sub seller occupation.
     *
     * @param string $occupation
     *
     * @return $this
     */
    public function setOccupation($occupation);

    /**
     * Get sub seller monthly gross income.
     *
     * @return float|null
     */
    public function getMonthlyGrossIncome();

    /**
     * Set sub seller monthly gross income.
     *
     * @param float $monthlyGrossIncome
     *
     * @return $this
     */
    public function setMonthlyGrossIncome($monthlyGrossIncome);

    /**
     * Get sub seller gross equity.
     *
     * @return float|null
     */
    public function getGrossEquity();

    /**
     * Set sub seller gross equity.
     *
     * @param float $grossEquity
     *
     * @return $this
     */
    public function setGrossEquity($grossEquity);

    /**
     * Get sub seller status.
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set sub seller status.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get sub seller status comment.
     *
     * @return string|null
     */
    public function getStatusComment();

    /**
     * Set sub seller status comment.
     *
     * @param string $statusComment
     *
     * @return $this
     */
    public function setStatusComment($statusComment);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Getnet\SubSellerMagento\Api\Data\SubSellerExtensionInterface $extensionAttributes
    );
}
