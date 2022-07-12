<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Model\Seller;

use Getnet\SubSellerMagento\Model\Config\Source;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Serialize\Serializer\Serialize;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

/**
 * Sub Seller Model converter.
 *
 * Converts a Sub Seller Model to a Data Object or vice versa.
 *
 * @SuppressWarnings(PHPMD)
 */
class Converter
{
    /**
     * @var Serialize
     */
    protected $serialize;

    /**
     * @var \Getnet\SubSellerMagento\Api\Data\SubSellerInterfaceFactory
     */
    protected $subSellerDataObjectFactory;

    /**
     * @var Source\Status
     */
    protected $status;

    /**
     * @var FormatInterface|null
     */
    private $format;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @param Serialize                                                   $serialize
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerInterfaceFactory $subSellerDataObjectFactory
     * @param Source\Status                                               $status
     * @param DateTime                                                    $date
     * @param TimezoneInterface                                           $timezone
     * @param FormatInterface|null                                        $format
     */
    public function __construct(
        Serialize $serialize,
        \Getnet\SubSellerMagento\Api\Data\SubSellerInterfaceFactory $subSellerDataObjectFactory,
        Source\Status $status,
        DateTime $date,
        TimezoneInterface $timezone,
        FormatInterface $format = null
    ) {
        $this->serialize = $serialize;
        $this->subSellerDataObjectFactory = $subSellerDataObjectFactory;
        $this->status = $status;
        $this->date = $date;
        $this->timezone = $timezone;
        $this->format = $format ?: ObjectManager::getInstance()->get(FormatInterface::class);
    }
    // phpcs:disable Generic.Files.LineLength

    /**
     * Extract sub seller data in a format which is.
     *
     * @param \Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller
     * @param bool                                                 $returnNumericLogic
     *
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPCS.Generic.Files.LineLength)
     */
    public function createArrayFromServiceObject(
        \Getnet\SubSellerMagento\Api\Data\SubSellerInterface $subSeller,
        $returnNumericLogic = false
    ) {
        $businessAddress = [];
        $bankAccountsUniqueAccount = [];
        $identificationDocument = [];
        $status = null;
        if ($subSeller->getStatus()) {
            $statusOptions = $this->status->toOptionArray();
            $status = 1;
            if ($subSeller->getStatus() <= 3) {
                $status = $statusOptions[$subSeller->getStatus()];
                $status = $status->getText();
            }
        }

        if ($subSeller->getAddresses()) {
            unset($businessAddress);
            $addresses = $subSeller->getAddresses();
            $businessAddress = $addresses['addresses']['business_address'];
        }

        if ($subSeller->getBankAccounts()) {
            unset($bankAccountsUniqueAccount);
            $bankAccounts = $subSeller->getBankAccounts();
            $bankAccountsUniqueAccount = $bankAccounts['bank_accounts']['unique_account'];
        }

        if ($subSeller->getIdentificationDocument()) {
            unset($identificationDocument);
            $identificationDocument = $subSeller->getIdentificationDocument();
            $identificationDocument = $identificationDocument['identification_document'];
        }

        $subSellerFormData = [
            'id'                                 => $subSeller->getId(),
            'merchant_id'                        => $subSeller->getMerchantId(),
            'code'                               => $subSeller->getCode(),
            'id_ext'                             => $subSeller->getIdExt(),
            'email'                              => $subSeller->getEmail(),
            'legal_document_number'              => $subSeller->getLegalDocumentNumber(),
            'type'                               => $subSeller->getType(),
            'legal_name'                         => $subSeller->getLegalName(),
            'birth_date'                         => $subSeller->getBirthDate(),
            'address_street'                     => isset($businessAddress['address_street']) ? $businessAddress['address_street'] : null,
            'address_street_number'              => isset($businessAddress['address_street_number']) ? $businessAddress['address_street_number'] : null,
            'address_street_district'            => isset($businessAddress['address_street_district']) ? $businessAddress['address_street_district'] : null,
            'address_street_complement'          => isset($businessAddress['address_street_complement']) ? $businessAddress['address_street_complement'] : null,
            'address_city'                       => isset($businessAddress['address_city']) ? $businessAddress['address_city'] : null,
            'address_region'                     => isset($businessAddress['address_region']) ? $businessAddress['address_region'] : null,
            'address_region_id'                  => isset($businessAddress['address_region_id']) ? $businessAddress['address_region_id'] : null,
            'address_country_id'                 => isset($businessAddress['address_country_id']) ? $businessAddress['address_country_id'] : null,
            'address_postcode'                   => isset($businessAddress['address_postcode']) ? $businessAddress['address_postcode'] : null,
            'telephone'                          => $subSeller->getTelephone(),
            'account_type'                       => isset($bankAccountsUniqueAccount['account_type']) ? $bankAccountsUniqueAccount['account_type'] : null,
            'bank'                               => isset($bankAccountsUniqueAccount['bank']) ? $bankAccountsUniqueAccount['bank'] : null,
            'agency'                             => isset($bankAccountsUniqueAccount['agency']) ? $bankAccountsUniqueAccount['agency'] : null,
            'agency_digit'                       => isset($bankAccountsUniqueAccount['agency_digit']) ? $bankAccountsUniqueAccount['agency_digit'] : null,
            'account'                            => isset($bankAccountsUniqueAccount['account']) ? $bankAccountsUniqueAccount['account'] : null,
            'account_digit'                      => isset($bankAccountsUniqueAccount['account_digit']) ? $bankAccountsUniqueAccount['account_digit'] : null,
            'payment_plan'                       => $subSeller->getPaymentPlan(),
            'accepted_contract'                  => $subSeller->getAcceptedContract(),
            'marketplace_store'                  => $subSeller->getMarketplaceStore(),
            'monthly_gross_income'               => $subSeller->getMonthlyGrossIncome(),
            'gross_equity'                       => $subSeller->getGrossEquity(),
            'status'                             => $status,
            'status_comment'                     => $subSeller->getStatusComment(),
            'created_at'                         => $subSeller->getCreatedAt(),
            'updated_at'                         => $subSeller->getUpdatedAt(),
        ];

        if ($subSellerFormData['address_region_id'] === '0') {
            $subSellerFormData['address_region_id'] = '';
        }

        if ($subSeller->getType()) {
            $subSellerFormData['type'] = $returnNumericLogic ? 1 : true;
        }

        return $subSellerFormData;
    }

    /**
     * Convert an array to a sub seller data object.
     *
     * @param array $formData
     *
     * @return \Getnet\SubSellerMagento\Api\Data\SubSellerInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function populateSubSellerData($formData)
    {
        $birthDate = $this->extractFormData($formData, 'birth_date');
        if (!$birthDate) {
            $birthDate = '01/01/1970';
        }

        $birthDate = $this->convertDate($birthDate);

        $status = $this->extractFormData($formData, 'status');
        $statusComment = $this->extractFormData($formData, 'status_comment');
        if ($status === 1) {
            $statusComment = null;
        }

        $subSeller = $this->subSellerDataObjectFactory->create();
        $subSeller->setId($this->extractFormData($formData, 'id'))
            ->setMerchantId($this->extractFormData($formData, 'merchant_id'))
            ->setCode($this->extractFormData($formData, 'code'))
            ->setEmail($this->extractFormData($formData, 'email'))
            ->setLegalDocumentNumber($this->extractFormData($formData, 'legal_document_number'))
            ->setType($this->extractFormData($formData, 'type'))
            ->setLegalName($this->extractFormData($formData, 'legal_name'))
            ->setBirthDate($birthDate)
            ->setAddresses($this->extractFormData($formData, 'addresses'))
            ->setTelephone($this->extractFormData($formData, 'telephone'))
            ->setFax($this->extractFormData($formData, 'fax'))
            ->setBankAccounts($this->extractFormData($formData, 'bank_accounts'))
            ->setPaymentPlan($this->extractFormData($formData, 'payment_plan'))
            ->setAcceptedContract($this->extractFormData($formData, 'accepted_contract'))
            ->setMarketplaceStore($this->extractFormData($formData, 'marketplace_store'))
            ->setOccupation($this->extractFormData($formData, 'occupation'))
            ->setMonthlyGrossIncome($this->extractFormData($formData, 'monthly_gross_income'))
            ->setGrossEquity($this->extractFormData($formData, 'gross_equity'))
            ->setStatus($status)
            ->setStatusComment($statusComment);

        return $subSeller;
    }

    /**
     * Determines if an array value is set in the form data array and returns it.
     *
     * @param array  $formData  the form to get data from
     * @param string $fieldName the key
     *
     * @return null|string
     */
    protected function extractFormData($formData, $fieldName)
    {
        if (isset($formData[$fieldName])) {
            return $formData[$fieldName];
        }

        return null;
    }

    /**
     * Convert Date.
     *
     * @param string $date
     *
     * @return string
     */
    public function convertDate(string $date): string
    {
        $date = str_replace('/', '-', $date);
        $defaultTimezone = $this->timezone->getDefaultTimezone();
        $configTimezone = $this->timezone->getConfigTimezone();
        $date = new \DateTime($date, new \DateTimeZone($configTimezone));
        $date->setTimezone(new \DateTimeZone($defaultTimezone));

        return $date->format('Y-m-d');
    }
}
