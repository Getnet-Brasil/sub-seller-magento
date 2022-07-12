<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Model\Seller;

use Getnet\SubSellerMagento\Helper\Data as SubSellerHelper;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Serialize\Serializer\Serialize;

/**
 * Sub Seller Model.
 *
 * @SuppressWarnings(PHPMD)
 */
class SubSeller extends \Magento\Framework\Model\AbstractExtensibleModel implements
    \Getnet\SubSellerMagento\Api\Data\SubSellerInterface
{
    /**#@+
     * Constants defined for keys of array, makes typos less likely
     */
    public const KEY_ID = 'id';
    public const LOAD_DATA_BY_CUSTOMER_ID = 'load_data_by_customer_id';
    public const KEY_CODE = 'code';
    public const MERCHANT_ID = 'merchant_id';
    public const ID_EXT = 'id_ext';
    public const EMAIL = 'email';
    public const LEGAL_DOCUMENT_NUMBER = 'legal_document_number';
    public const TYPE = 'type';
    public const LEGAL_NAME = 'legal_name';
    public const BIRTH_DATE = 'birth_date';
    public const MONTHLY_GROSS_INCOME = 'monthly_gross_income';
    public const GROSS_EQUITY = 'gross_equity';
    public const OCCUPATION = 'occupation';
    public const MAILING_ADDRESS_EQUALS = 'mailing_address_equals';
    public const ADDRESSES = 'addresses';
    public const BUSINESS_ADDRESS = 'business_address';
    public const MAILING_ADDRESS = 'mailing_address';
    public const TELEPHONE = 'telephone';
    public const BANK_ACCOUNTS = 'bank_accounts';
    public const BANK_ACCOUNTS_UNIQUE_ACCOUNT = 'unique_account';
    public const ACCEPTED_CONTRACT = 'accepted_contract';
    public const MARKETPLACE_STORE = 'marketplace_store';
    public const PAYMENT_PLAN = 'payment_plan';
    public const STATUS = 'status';
    public const STATUS_COMMENT = 'status_comment';
    /**#@-*/

    /**
     * @var Serialize
     */
    protected $serialize;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @var SubSellerHelper
     */
    protected $subSellerHelper;

    /**
     * @param \Magento\Framework\Model\Context                        $context
     * @param \Magento\Framework\Registry                             $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory       $extensionFactory
     * @param AttributeValueFactory                                   $customAttributeFactory
     * @param Serialize                                               $serialize
     * @param CustomerRepositoryInterface                             $customerRepository
     * @param AddressRepositoryInterface                              $addressRepository
     * @param SubSellerHelper                                         $subSellerHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb           $resourceCollection
     * @param array                                                   $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        Serialize $serialize,
        CustomerRepositoryInterface $customerRepository,
        AddressRepositoryInterface $addressRepository,
        SubSellerHelper $subSellerHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->serialize = $serialize;
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
        $this->subSellerHelper = $subSellerHelper;
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Magento model constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Getnet\SubSellerMagento\Model\ResourceModel\Seller\SubSeller::class);
    }

    /**
     * Prepare location settings and sub seller postcode before save rate.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return \Getnet\SubSellerMagento\Model\Seller\SubSeller
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function beforeSave()
    {
        $this->_eventManager->dispatch('subseller_settings_change_before');
        parent::beforeSave();

        return $this;
    }

    /**
     * Save sub seller.
     *
     * @return \Getnet\SubSellerMagento\Model\Seller\SubSeller
     */
    public function afterSave()
    {
        $this->_eventManager->dispatch('subseller_settings_change_after');

        return parent::afterSave();
    }

    /**
     * Processing object before delete data.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return \Getnet\SubSellerMagento\Model\Seller\SubSeller
     */
    public function beforeDelete()
    {
        $this->_eventManager->dispatch('subseller_settings_delete_before');

        return parent::beforeDelete();
    }

    /**
     * After rate delete.
     *
     * Redeclared for dispatch subseller_settings_change_after event
     *
     * @return \Getnet\SubSellerMagento\Model\Seller\SubSeller
     */
    public function afterDelete()
    {
        $this->_eventManager->dispatch('subseller_settings_change_after');

        return parent::afterDelete();
    }

    /**
     * Load sub seller model by code.
     *
     * @param string $code
     *
     * @return \Getnet\SubSellerMagento\Model\Seller\SubSeller
     */
    public function loadByCode($code)
    {
        $this->load($code, 'code');

        return $this;
    }

    /**
     * Get Sub Seller Id.
     *
     * @return int
     */
    public function getSubSellerId()
    {
        return $this->getData(self::KEY_ID);
    }

    /**
     * Set Sub Seller Id.
     *
     * @param int $sellerId
     *
     * @return $this
     */
    public function setSubSellerId($sellerId)
    {
        return $this->setData(self::KEY_ID, $sellerId);
    }

    /**
     * Get Sub Seller by customer id.
     *
     * @return int
     */
    public function getLoadDataByCustomerId()
    {
        return $this->getData(self::LOAD_DATA_BY_CUSTOMER_ID);
    }

    /**
     * Set Sub Seller load data by customer id.
     *
     * @param int $customerId
     *
     * @return $this
     */
    public function setLoadDataByCustomerId($customerId)
    {
        if ($customerId) {
            $this->populateSubSellerDataByCustomerId($customerId);
        }

        return $this->setData(self::LOAD_DATA_BY_CUSTOMER_ID, $customerId);
    }

    /**
     * Get Merchant Id.
     *
     * @return string;
     */
    public function getMerchantId()
    {
        if (!$this->getData(self::MERCHANT_ID)) {
            $merchantId = $this->subSellerHelper->getMerchantId();

            return $merchantId;
        }

        return $this->getData(self::MERCHANT_ID);
    }

    /**
     * Set Merchant Id.
     *
     * @param string $merchantId
     *
     * @return $this
     */
    public function setMerchantId($merchantId)
    {
        return $this->setData(self::MERCHANT_ID, $merchantId);
    }

    /**
     * Get Code.
     */
    public function getCode()
    {
        return $this->getData(self::KEY_CODE);
    }

    /**
     * Get Code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        return $this->setData(self::KEY_CODE, $code);
    }

    /**
     * Get Id Ext.
     *
     * @return string;
     */
    public function getIdExt()
    {
        return $this->getData(self::ID_EXT);
    }

    /**
     * Set Id Ext.
     *
     * @param string $idExt
     *
     * @return $this
     */
    public function setIdExt($idExt)
    {
        return $this->setData(self::ID_EXT, $idExt);
    }

    /**
     * Get Sub Seller Email.
     *
     * @return string;
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Get Sub Seller Email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get Sub Seller legal document number.
     *
     * @return string;
     */
    public function getLegalDocumentNumber()
    {
        return $this->getData(self::LEGAL_DOCUMENT_NUMBER);
    }

    /**
     * Get Sub Seller legal document number.
     *
     * @param string $legalDocumentNumber
     *
     * @return $this
     */
    public function setLegalDocumentNumber($legalDocumentNumber)
    {
        return $this->setData(self::LEGAL_DOCUMENT_NUMBER, $legalDocumentNumber);
    }

    /**
     * Get Sub Seller type.
     *
     * @return string;
     */
    public function getType()
    {
        return $this->getData(self::TYPE);
    }

    /**
     * Get Sub Seller type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Get Sub Seller legal name.
     *
     * @return string;
     */
    public function getLegalName()
    {
        return $this->getData(self::LEGAL_NAME);
    }

    /**
     * Set Sub Seller legal name.
     *
     * @param string $legalName
     *
     * @return $this
     */
    public function setLegalName($legalName)
    {
        return $this->setData(self::LEGAL_NAME, $legalName);
    }

    /**
     * Get Sub Seller birth date.
     *
     * @return string;
     */
    public function getBirthDate()
    {
        return $this->getData(self::BIRTH_DATE);
    }

    /**
     * Set Sub Seller birth date.
     *
     * @param string $birthDate
     *
     * @return $this
     */
    public function setBirthDate($birthDate)
    {
        return $this->setData(self::BIRTH_DATE, $birthDate);
    }

    /**
     * Get Addresses.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\AddressesInterface[]|null
     */
    public function getAddresses()
    {
        if ($this->getData(self::ADDRESSES)) {
            return $this->serialize->unserialize($this->getData(self::ADDRESSES));
        }

        return $this->getData(self::ADDRESSES);
    }

    /**
     * Set Addresses.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\AddressesInterface[] $addresses
     *
     * @return \Getnet\SubSellerMagento\Api\Data\AddressesInterface
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function setAddresses(
        array $addresses = null
    ) {
        $addressesFormat = [];
        foreach ($addresses as $key => $address) {
            $regionName = isset($address['address_region']) ? $address['address_region'] : null;
            $regionId = isset($address['address_region_id']) ? $address['address_region_id'] : null;

            if ($regionId) {
                $regionName = $this->subSellerHelper->getRegionNameByRegionId($regionId);
            }

            if ($regionName) {
                $regionId = $this->subSellerHelper->getRegionIdByRegionName($regionName);
            }

            $complement = isset($address['address_street_complement']) ? $address['address_street_complement'] : null;

            if ($key === 0) {
                $addressesFormat[self::ADDRESSES][self::BUSINESS_ADDRESS] = [
                    'address_street'            => $address['address_street'],
                    'address_street_number'     => $address['address_street_number'],
                    'address_street_district'   => $address['address_street_district'],
                    'address_street_complement' => isset($complement) ? $complement : null,
                    'address_city'              => $address['address_city'],
                    'address_region'            => $regionName,
                    'address_region_id'         => $regionId,
                    'address_postcode'          => $address['address_postcode'],
                    'address_country_id'        => $address['address_country_id'],
                ];
            }
            if ($key === 1) {
                $addressesFormat[self::ADDRESSES][self::MAILING_ADDRESS] = [
                    'address_street'            => $address['address_street'],
                    'address_street_number'     => $address['address_street_number'],
                    'address_street_district'   => $address['address_street_district'],
                    'address_street_complement' => isset($complement) ? $complement : null,
                    'address_city'              => $address['address_city'],
                    'address_region'            => isset($address['address_region']) ? $address['address_region'] : null,
                    'address_region_id'         => $address['address_region_id'],
                    'address_postcode'          => $address['address_postcode'],
                    'address_country_id'        => $address['address_country_id'],
                ];
            }
        }

        return $this->setData(self::ADDRESSES, $this->serialize->serialize($addressesFormat));
    }

    /**
     * Get Sub Seller telefone.
     *
     * @return string;
     */
    public function getTelephone()
    {
        return $this->getData(self::TELEPHONE);
    }

    /**
     * Set Sub Seller telefone.
     *
     * @param string $telefone
     *
     * @return $this
     */
    public function setTelephone($telefone)
    {
        return $this->setData(self::TELEPHONE, $telefone);
    }

    /**
     * Get Bank Accounts.
     *
     * @return \Getnet\SubSellerMagento\Api\Data\BankAccountsInterface[]|null
     */
    public function getBankAccounts()
    {
        if ($this->getData(self::BANK_ACCOUNTS)) {
            return $this->serialize->unserialize($this->getData(self::BANK_ACCOUNTS));
        }

        return $this->getData(self::BANK_ACCOUNTS);
    }

    /**
     * Set Bank Accounts.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\BankAccountsInterface[] $bankAccounts
     *
     * @return \Getnet\SubSellerMagento\Api\Data\BankAccountsInterface
     */
    public function setBankAccounts(
        array $bankAccounts = null
    ) {
        $bankAccountsFormat = [];
        foreach ($bankAccounts as $key => $account) {
            if ($key === 0) {
                $bankAccountsFormat[self::BANK_ACCOUNTS][self::BANK_ACCOUNTS_UNIQUE_ACCOUNT] = [
                    'account_type'  => $account['account_type'],
                    'bank'          => $account['bank'],
                    'agency'        => $account['agency'],
                    'agency_digit'  => isset($account['agency_digit']) ? $account['agency_digit'] : null,
                    'account'       => $account['account'],
                    'account_digit' => isset($account['account_digit']) ? $account['account_digit'] : null,
                ];
            }
        }

        return $this->setData(self::BANK_ACCOUNTS, $this->serialize->serialize($bankAccountsFormat));
    }

    /**
     * Get Sub Seller payment plan.
     *
     * @return int;
     */
    public function getPaymentPlan()
    {
        return $this->getData(self::PAYMENT_PLAN);
    }

    /**
     * Set Sub Seller payment plan.
     *
     * @param int $paymentPlan
     *
     * @return $this
     */
    public function setPaymentPlan($paymentPlan)
    {
        return $this->setData(self::PAYMENT_PLAN, $paymentPlan);
    }

    /**
     * Get Sub Seller accepted contract.
     *
     * @return bool;
     */
    public function getAcceptedContract()
    {
        return $this->getData(self::ACCEPTED_CONTRACT);
    }

    /**
     * Set Sub Seller accepted contract.
     *
     * @param bool $acceptedContract
     *
     * @return $this
     */
    public function setAcceptedContract($acceptedContract)
    {
        return $this->setData(self::ACCEPTED_CONTRACT, $acceptedContract);
    }

    /**
     * Get Sub Seller marketplace store.
     *
     * @return bool;
     */
    public function getMarketplaceStore()
    {
        return $this->getData(self::MARKETPLACE_STORE);
    }

    /**
     * Set Sub Seller marketplace store.
     *
     * @param bool $marketplaceStore
     *
     * @return $this
     */
    public function setMarketplaceStore($marketplaceStore)
    {
        return $this->setData(self::MARKETPLACE_STORE, $marketplaceStore);
    }

    /**
     * Get Sub Seller occupation.
     *
     * @return string;
     */
    public function getOccupation()
    {
        if (!$this->getData(self::OCCUPATION)) {
            return 'Diversos';
        }

        return $this->getData(self::OCCUPATION);
    }

    /**
     * Set Sub Seller occupation.
     *
     * @param string $ccupation
     *
     * @return $this
     */
    public function setOccupation($ccupation)
    {
        return $this->setData(self::OCCUPATION, $ccupation);
    }

    /**
     * Get Sub Seller monthly gross income.
     *
     * @return float;
     */
    public function getMonthlyGrossIncome()
    {
        return $this->getData(self::MONTHLY_GROSS_INCOME);
    }

    /**
     * Get Sub Seller monthly gross income.
     *
     * @param float $monthlyGrossIncome
     *
     * @return $this
     */
    public function setMonthlyGrossIncome($monthlyGrossIncome)
    {
        return $this->setData(self::MONTHLY_GROSS_INCOME, $monthlyGrossIncome);
    }

    /**
     * Get Sub Seller gross equity.
     *
     * @return float;
     */
    public function getGrossEquity()
    {
        return $this->getData(self::GROSS_EQUITY);
    }

    /**
     * Set Sub Seller gross equity.
     *
     * @param float $grossEquity
     *
     * @return $this
     */
    public function setGrossEquity($grossEquity)
    {
        return $this->setData(self::GROSS_EQUITY, $grossEquity);
    }

    /**
     * Get Sub Seller status.
     *
     * @return int;
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    /**
     * Set Sub Seller status.
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get Sub Seller status comment.
     *
     * @return int;
     */
    public function getStatusComment()
    {
        return $this->getData(self::STATUS_COMMENT);
    }

    /**
     * Set Sub Seller status comment.
     *
     * @param string $statusComment
     *
     * @return $this
     */
    public function setStatusComment($statusComment)
    {
        return $this->setData(self::STATUS_COMMENT, $statusComment);
    }

    /**
     * @inheritdoc
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Getnet\SubSellerMagento\Api\Data\SubSellerExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Populate Sub Seller Data by Customer Id.
     *
     * @param int $customerId
     *
     * @return void;
     */
    public function populateSubSellerDataByCustomerId($customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        $this->setCode('seller_'.$customer->getId());
        $this->setEmail($customer->getEmail());
        $this->setLegalDocumentNumber($customer->getTaxvat());
        $this->setLegalName($customer->getFirstname().' '.$customer->getLastname());
        $this->setBirthDate($customer->getDob());
        $addressId = $customer->getDefaultBilling();

        if ($addressId) {
            $addresses = [];
            $address = $this->addressRepository->getById($addressId);
            $this->setMailingAddressEqualson(true);
            $streets = $address->getStreet();
            $addresses[0] = [
                'address_street'            => $streets[0],
                'address_street_number'     => $streets[1],
                'address_street_district'   => isset($streets[2]) ? $streets[2] : $streets[3],
                'address_street_complement' => isset($streets[3]) ? $streets[3] : null,
                'address_city'              => $address->getCity(),
                'address_region'            => $address->getRegion()->getRegionCode(),
                'address_region_id'         => $address->getRegionId(),
                'address_postcode'          => $address->getPostcode(),
                'address_country_id'        => $address->getCountryId(),
            ];
            $this->setAddresses($addresses);
            $this->setTelephone($address->getTelephone());

            if ($address->getVatId()) {
                $this->setLegalDocumentNumber($address->getVatId());
            }
        }
    }
}
