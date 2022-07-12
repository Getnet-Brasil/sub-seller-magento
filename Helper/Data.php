<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Helper;

use Getnet\SubSellerMagento\Api\Data\SubSellerInterface;
use Getnet\SubSellerMagento\Model\Config;
use Getnet\SubSellerMagento\Model\Config\Source;
use Magento\Directory\Model\Region;
use Magento\Directory\Model\RegionFactory;
use Magento\Store\Model\Store;

/**
 * Sub Seller Helper.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Config
     */
    protected $configSubSeller;

    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var Region
     */
    protected $directoryRegion;

    /**
     * Constructor.
     *
     * @param Config        $configSubSeller
     * @param RegionFactory $regionFactory
     * @param Region        $directoryRegion
     */
    public function __construct(
        Config $configSubSeller,
        RegionFactory $regionFactory,
        Region $directoryRegion
    ) {
        $this->configSubSeller = $configSubSeller;
        $this->regionFactory = $regionFactory;
        $this->directoryRegion = $directoryRegion;
    }

    /**
     * Get Merchant Id.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return string
     */
    public function getMerchantId($store = null)
    {
        return $this->configSubSeller->getMerchantId($store);
    }

    /**
     * Get Region Name By Region Id.
     *
     * @param string $regionId
     *
     * @return string
     */
    public function getRegionNameByRegionId(string $regionId)
    {
        return $this->directoryRegion->load($regionId)->getCode();
    }

    /**
     * Get Region Id By Region Id.
     *
     * @param string      $regionName
     * @param string|null $countryId
     *
     * @return string
     */
    public function getRegionIdByRegionName(string $regionName, string $countryId = 'BR')
    {
        return $this->regionFactory->create()
            ->loadByCode($regionName, $countryId)
            ->getId();
    }

    /**
     * Get List Commissions Formated.
     *
     * @param null|string|bool|int|Store $store
     *
     * @return array|string
     */
    public function getListCommissionsFormated($store = null)
    {
        $list = $this->configSubSeller->getListCommissions($store);
        $listCommissions = [];

        foreach ($list as $commission) {
            $listCommissions[] = [
                'brand'                 => $commission['brand'],
                'product'               => $commission['product'],
                'commission_percentage' => (int) $commission['commission_percentage'],
                'payment_plan'          => (int) $commission['payment_plan'],
            ];
        }

        return $listCommissions;
    }

    /**
     * Get List Commissions Formated.
     *
     * @param string $text
     *
     * @return array
     */
    public function removeAcento(string $text): string
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $text);
    }

    /**
     * Format Data to Send PF.
     *
     * @param SubSellerInterface $subSeller
     * @param string             $typeFormat
     *
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function formatedDataPJ(
        SubSellerInterface $subSeller,
        string $typeFormat = null
    ) {
        $data = [];

        $addresses = $subSeller->getAddresses();
        $businessAddress = $addresses['addresses']['business_address'];
        $bankAccounts = $subSeller->getBankAccounts();
        $bankAccounts = array_merge($bankAccounts['bank_accounts'], ['type_accounts' => 'unique']);
        
        $phone = preg_replace('/[^0-9]/', '', $subSeller->getTelephone());
        $namePhone = 'phone';
        if (strlen($phone) === 11) {
            $namePhone = 'cellphone';
        }

        $data = [
            'merchant_id'                  => $subSeller->getMerchantId(),
            'subsellerid_ext'              => $subSeller->getCode(),
            'email'                        => $subSeller->getEmail(),
            'legal_document_number'        => (int) preg_replace('/[^0-9]/', '', $subSeller->getLegalDocumentNumber()),
            'legal_name'                   => $this->removeAcento($subSeller->getLegalName()),
            'trade_name'                   => $this->removeAcento($subSeller->getLegalName()),
            $namePhone                     => [
                'area_code'    => $this->getNumberOrDDD($phone, true),
                'phone_number' => $this->getNumberOrDDD($phone, false),
            ],
            'business_address' => [
                'mailing_address_equals' => 'S',
                'street'                 => $businessAddress['address_street'],
                'number'                 => $businessAddress['address_street_number'],
                'district'               => $businessAddress['address_street_district'],
                'suite'                  => $businessAddress['address_street_complement'],
                'city'                   => $businessAddress['address_city'],
                'state'                  => $businessAddress['address_region'],
                'postal_code'            => preg_replace('/[^0-9]/', '', $businessAddress['address_postcode']),
                'country'                => $businessAddress['address_country_id'],
            ],
            'bank_accounts'               => $bankAccounts,
            'payment_plan'                => $subSeller->getPaymentPlan(),
            'accepted_contract'           => $subSeller->getAcceptedContract() ? 'S' : 'N',
            'marketplace_store'           => $subSeller->getMarketplaceStore() ? 'S' : 'N',
            'occupation'                  => $this->removeAcento($subSeller->getOccupation()),
            'list_commissions'            => $this->getListCommissionsFormated(),
            'state_fiscal_document_number' => 'ISENTO',
            'federal_registration_status' => 'active',
        ];

        if ($subSeller->getIdExt()) {
            $data = array_merge($data, [
                'subseller_id' => $subSeller->getIdExt(),
            ]);
        }

        if ($typeFormat) {
            $data = $this->replaceKeyInData($data, $typeFormat);
        }

        return $data;
    }

    /**
     * Format Data to Send PF.
     *
     * @param SubSellerInterface $subSeller
     * @param string             $typeFormat
     *
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function formatedDataPF(
        SubSellerInterface $subSeller,
        string $typeFormat = null
    ) {
        $data = [];

        $addresses = $subSeller->getAddresses();
        $businessAddress = $addresses['addresses']['business_address'];
        $bankAccounts = $subSeller->getBankAccounts();
        $bankAccounts = array_merge($bankAccounts['bank_accounts'], ['type_accounts' => 'unique']);
        $phone = preg_replace('/[^0-9]/', '', $subSeller->getTelephone());
        $namePhone = 'phone';
        if (strlen($phone) === 11) {
            $namePhone = 'cellphone';
        }

        $data = [
            'merchant_id'           => $subSeller->getMerchantId(),
            'subsellerid_ext'       => $subSeller->getCode(),
            'email'                 => $subSeller->getEmail(),
            'legal_document_number' => (int) preg_replace('/[^0-9]/', '', $subSeller->getLegalDocumentNumber()),
            'legal_name'            => $this->removeAcento($subSeller->getLegalName()),
            'birth_date'            => $subSeller->getBirthDate(),
            'business_address'      => [
                'mailing_address_equals' => 'S',
                'street'                 => $businessAddress['address_street'],
                'number'                 => $businessAddress['address_street_number'],
                'district'               => $businessAddress['address_street_district'],
                'suite'                  => $businessAddress['address_street_complement'],
                'city'                   => $businessAddress['address_city'],
                'state'                  => $businessAddress['address_region'],
                'postal_code'            => preg_replace('/[^0-9]/', '', $businessAddress['address_postcode']),
                'country'                => $businessAddress['address_country_id'],
            ],
            $namePhone => [
                'area_code'    => $this->getNumberOrDDD($phone, true),
                'phone_number' => $this->getNumberOrDDD($phone, false),
            ],
            'bank_accounts'          => $bankAccounts,
            'payment_plan'           => $subSeller->getPaymentPlan(),
            'accepted_contract'      => $subSeller->getAcceptedContract() ? 'S' : 'N',
            'marketplace_store'      => $subSeller->getMarketplaceStore() ? 'S' : 'N',
            'occupation'             => $this->removeAcento($subSeller->getOccupation()),
            'list_commissions'       => $this->getListCommissionsFormated(),
        ];

        if ($subSeller->getIdExt()) {
            $data = array_merge($data, [
                'subseller_id' => $subSeller->getIdExt(),
            ]);
        }

        if ($typeFormat) {
            $data = $this->replaceKeyInData($data, $typeFormat);
        }

        return $data;
    }

    /**
     * Format Data to Send.
     *
     * @param SubSellerInterface $subSeller
     * @param bool               $type
     * @param string             $typeFormat
     *
     * @return array
     */
    public function formatedData(
        SubSellerInterface $subSeller,
        bool $type,
        string $typeFormat = null
    ) {
        if (!$type) {
            return $this->formatedDataPF($subSeller, $typeFormat);
        }

        return $this->formatedDataPJ($subSeller, $typeFormat);
    }

    /**
     * Replace Key in Data.
     *
     * @param array  $data
     * @param string $newTypeFormat
     *
     * @return array
     */
    public function replaceKeyInData(array $data, string $newTypeFormat): array
    {
        if ($newTypeFormat === 'seller_update') {
            $data['naturalized'] = 'S';
            $data['ppe_indication'] = 'not_applied';
            $data['ppe_description'] = 'NAO APLICADO';
            unset($data['business_address']['mailing_address_equals']);
            unset($data['identification_document']['document_issuer_state_id']);
            $data['residential_address'] = $data['business_address'];
        }

        return $data;
    }

    /**
     * Get Number or DDD.
     *
     * @param string $param_telefone
     * @param bool   $return_ddd
     *
     * @return string
     */
    public function getNumberOrDDD($param_telefone, $return_ddd = false)
    {
        $cust_ddd = '11';
        $cust_telephone = preg_replace('/[^0-9]/', '', $param_telefone);
        if (strlen($cust_telephone) == 11) {
            $str = strlen($cust_telephone) - 9;
            $indice = 9;
        } else {
            $str = strlen($cust_telephone) - 8;
            $indice = 8;
        }

        if ($str > 0) {
            $cust_ddd = substr($cust_telephone, 0, 2);
            $cust_telephone = substr($cust_telephone, $str, $indice);
        }
        if ($return_ddd === false) {
            $number = $cust_telephone;
        } else {
            $number = $cust_ddd;
        }

        return preg_replace('/[^0-9]/', '', $number);
    }
}
