<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Admin Form Sub Seller class add form.
 */
declare(strict_types=1);

namespace Getnet\SubSellerMagento\Block\Adminhtml\SubSeller;

use Getnet\SubSellerMagento\Controller\RegistryConstants;
use Getnet\SubSellerMagento\Model\Config\Source;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Sub Seller form.
 *
 * @api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @since 100.0.2
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    public const FORM_ELEMENT_ID = 'sub-seller-form';

    /**
     * @var null
     */
    protected $_titles = null;

    /**
     * @var string
     */
    protected $_template = 'Getnet_SubSellerMagento::sub_seller/form.phtml';

    /**
     * @var \Getnet\SubSellerMagento\Helper\Data|null
     */
    protected $helperDataSubSeller = null;

    /**
     * @var \Magento\Directory\Model\Config\Source\Country
     */
    protected $_country;

    /**
     * @var \Magento\Directory\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * @var \Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface
     */
    protected $_subSellerRepository;

    /**
     * @var \Getnet\SubSellerMagento\Model\SubSellerCollection
     */
    protected $_subSellerCollection;

    /**
     * @var \Getnet\SubSellerMagento\Model\Seller\Converter
     */
    protected $_subSellerConverter;

    /**
     * @var Source\BankTypeAccounts
     */
    protected $bankTypeAccounts;

    /**
     * @var YesNo
     */
    protected $yesNo;

    /**
     * @param \Magento\Backend\Block\Template\Context                   $context
     * @param \Magento\Framework\Registry                               $registry
     * @param \Magento\Framework\Data\FormFactory                       $formFactory
     * @param \Magento\Directory\Model\RegionFactory                    $regionFactory
     * @param \Magento\Directory\Model\Config\Source\Country            $country
     * @param \Getnet\SubSellerMagento\Helper\Data                      $helperDataSubSeller
     * @param \Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface $subSellerRepository
     * @param \Getnet\SubSellerMagento\Model\SubSellerCollection        $subSellerCollection
     * @param \Getnet\SubSellerMagento\Model\Seller\Converter           $subSellerConverter
     * @param Source\BankTypeAccounts                                   $bankTypeAccounts
     * @param Source\BankTypeAccount                                    $bankTypeAccount
     * @param Yesno                                                     $yesNo
     * @param array                                                     $data
     * @param DirectoryHelper|null                                      $directoryHelper
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\Config\Source\Country $country,
        \Getnet\SubSellerMagento\Helper\Data $helperDataSubSeller,
        \Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface $subSellerRepository,
        \Getnet\SubSellerMagento\Model\SubSellerCollection $subSellerCollection,
        \Getnet\SubSellerMagento\Model\Seller\Converter $subSellerConverter,
        Source\BankTypeAccounts $bankTypeAccounts,
        Source\BankTypeAccount $bankTypeAccount,
        Yesno $yesNo,
        array $data = [],
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->_regionFactory = $regionFactory;
        $this->_country = $country;
        $this->helperDataSubSeller = $helperDataSubSeller;
        $this->_subSellerRepository = $subSellerRepository;
        $this->_subSellerCollection = $subSellerCollection;
        $this->_subSellerConverter = $subSellerConverter;
        $this->bankTypeAccounts = $bankTypeAccounts;
        $this->bankTypeAccount = $bankTypeAccount;
        $this->yesNo = $yesNo;
        $data['directoryHelper'] = $directoryHelper ?? ObjectManager::getInstance()->get(DirectoryHelper::class);
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDestElementId(self::FORM_ELEMENT_ID);
    }

    /**
     * Prepare form before rendering HTML.
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $subSellerId = $this->_coreRegistry->registry(RegistryConstants::CURRENT_SUB_SELLER_ID);

        $subSellerType = (int) $this->_coreRegistry->registry(RegistryConstants::CURRENT_SUB_SELLER_TYPE);

        try {
            if ($subSellerId) {
                $subSellerDataObject = $this->_subSellerRepository->get($subSellerId);
            }
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (NoSuchEntityException $e) {
            //sub seller not found//
        }

        $sessionFormValues = (array) $this->_coreRegistry->registry(RegistryConstants::CURRENT_SUB_SELLER_FORM_DATA);
        $formData = isset($subSellerDataObject)
            ? $this->_subSellerConverter->createArrayFromServiceObject($subSellerDataObject)
            : [];
        $formData = array_merge($formData, $sessionFormValues);

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $bankTypeAccount = $this->bankTypeAccount->toOptionArray();
        $dateFormat = $this->_localeDate->getDateFormatWithLongYear();
        $yesNo = $this->yesNo->toArray();

        $countries = $this->_country->toOptionArray(false, 'BR');
        unset($countries[0]);

        if (!isset($formData['address_country_id'])) {
            $formData['address_country_id'] = 'BR';
        }

        $regionCollection = $this->_regionFactory->create()->getCollection()->addCountryFilter(
            $formData['address_country_id']
        );

        $regions = $regionCollection->toOptionArray();

        if (isset($formData['type'])) {
            $subSellerType = (int) $formData['type'];
        }

        $legend = $this->getShowLegend() ? __('Sub Seller') : '';
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => $legend, 'class' => 'admin__scope-old form-inline']
        );

        if (isset($formData['id']) && $formData['id'] > 0) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id', 'value' => $formData['id']]
            );
        }

        if (isset($formData['status'])) {
            if ($formData['status'] !== 1) {
                $fieldset->addField(
                    'status',
                    'label',
                    [
                        'name'  => 'status',
                        'label' => __('Status'),
                        'title' => __('Status'),
                    ]
                );
            } else {
                $fieldset->addField(
                    'status',
                    'hidden',
                    [
                        'name'  => 'status',
                        'label' => __('Status'),
                        'title' => __('Status'),
                    ]
                );
            }
        }

        if (isset($formData['status_comment'])) {
            $fieldset->addField(
                'status_comment',
                'label',
                [
                    'name'  => 'status',
                    'label' => __('Status Comment'),
                    'title' => __('Status Comment'),
                ]
            );
        }

        if (isset($formData['created_at'])) {
            $fieldset->addField(
                'created_at',
                'label',
                [
                    'name'  => 'created_at',
                    'label' => __('Created At'),
                    'title' => __('Status Comment'),
                ]
            );
        }

        if (isset($formData['updated_at'])) {
            $fieldset->addField(
                'updated_at',
                'label',
                [
                    'name'  => 'updated_at',
                    'label' => __('Updated At'),
                    'title' => __('Updated At'),
                ]
            );
        }

        if (isset($formData['id_ext'])) {
            $fieldset->addField(
                'id_ext',
                'label',
                [
                    'name'  => 'id_ext',
                    'label' => __('Sub Seller Id on Getnet'),
                    'title' => __('Sub Seller Id on Getnet'),
                ]
            );
        }

        $fieldset = $form->addFieldset(
            'base_fieldset_data',
            ['legend' => 'Base Data', 'class' => 'admin__scope-old form-inline']
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name'     => 'code',
                'label'    => __('Code'),
                'title'    => __('Code'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'type',
            'hidden',
            [
                'name'  => 'type',
                'label' => __('Type Persona'),
                'title' => __('Type Persona'),
                'value' => isset($subSellerType) ? $subSellerType : 0,
            ]
        );

        // validate-email...
        $fieldset->addField(
            'email',
            'text',
            [
                'name'     => 'email',
                'label'    => __('Email'),
                'title'    => __('Email'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'legal_document_number',
            'text',
            [
                'name'     => 'legal_document_number',
                'label'    => __('Legal Document Number'),
                'title'    => __('Legal Document Number'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        if (!$subSellerType) {
            $fieldset->addField(
                'legal_name',
                'text',
                [
                    'name'     => 'legal_name',
                    'label'    => __('Legal Name'),
                    'title'    => __('Legal Name'),
                    'class'    => 'required-entry',
                    'required' => true,
                ]
            );
        }

        if ($subSellerType) {
            $fieldset->addField(
                'legal_name',
                'text',
                [
                    'name'     => 'legal_name',
                    'label'    => __('Trade Legal Name'),
                    'title'    => __('Trade Legal Name'),
                    'class'    => 'required-entry',
                    'required' => true,
                ]
            );
        }

        $fieldset->addField(
            'birth_date',
            'date',
            [
                'name'        => 'birth_date',
                'label'       => $subSellerType ? __('Founding Date') : __('Birth Date'),
                'title'       => $subSellerType ? __('Founding Date') : __('Birth Date'),
                'date_format' => $dateFormat,
                'class'       => 'required-entry',
                'required'    => true,
            ]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset_addresses',
            [
                'legend' => $this->getFormLabelsBySellerType($subSellerType, 'base_fieldset_addresses'),
            ]
        );

        $fieldset->addField(
            'address_street',
            'text',
            [
                'name'     => 'addresses[0][address_street]',
                'label'    => __('Street'),
                'title'    => __('Street'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'address_street_number',
            'text',
            [
                'name'     => 'addresses[0][address_street_number]',
                'label'    => __('Street Number'),
                'title'    => __('Street Number'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'address_street_district',
            'text',
            [
                'name'     => 'addresses[0][address_street_district]',
                'label'    => __('District'),
                'title'    => __('District'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'address_street_complement',
            'text',
            [
                'name'  => 'addresses[0][address_street_complement]',
                'label' => __('Complement'),
                'title' => __('Complement'),
            ]
        );

        $fieldset->addField(
            'address_city',
            'text',
            [
                'name'     => 'addresses[0][address_city]',
                'label'    => __('City'),
                'title'    => __('City'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        if (isset($formData['address_region'])) {
            $fieldset->addField(
                'address_region',
                'hidden',
                [
                    'name'  => 'addresses[0][address_region]',
                    'style' => 'display:none',
                ]
            );
        }

        $fieldset->addField(
            'address_region_id',
            'select',
            [
                'name'     => 'addresses[0][address_region_id]',
                'label'    => __('State'),
                'values'   => $regions,
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'address_country_id',
            'select',
            [
                'name'     => 'addresses[0][address_country_id]',
                'label'    => __('Country'),
                'required' => true,
                'values'   => $countries,
            ]
        );

        $fieldset->addField(
            'address_postcode',
            'text',
            [
                'name'     => 'addresses[0][address_postcode]',
                'label'    => __('Postcode'),
                'title'    => __('Postcode'),
                'class'    => 'required-entry validate-zip-br',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'telephone',
            'text',
            [
                'name'     => 'telephone',
                'label'    => __('Telephone'),
                'title'    => __('Telephone'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset_bank', ['legend' => __('Bank Account')]);

        $fieldset->addField(
            'account_type',
            'select',
            [
                'name'     => 'bank_accounts[0][account_type]',
                'label'    => __('Account Type'),
                'title'    => __('Account Type'),
                'values'   => $bankTypeAccount,
                'value'    => isset($formData['account_type']) ? $formData['account_type'] : 'C',
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'bank',
            'text',
            [
                'name'     => 'bank_accounts[0][bank]',
                'label'    => __('Bank'),
                'title'    => __('Bank'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'agency',
            'text',
            [
                'name'     => 'bank_accounts[0][agency]',
                'label'    => __('Agency'),
                'title'    => __('Agency'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'agency_digit',
            'text',
            [
                'name'     => 'bank_accounts[0][agency_digit]',
                'label'    => __('Agency Digit'),
                'title'    => __('Agency Digit'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'account',
            'text',
            [
                'name'     => 'bank_accounts[0][account]',
                'label'    => __('Account'),
                'title'    => __('Account'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'account_digit',
            'text',
            [
                'name'     => 'bank_accounts[0][account_digit]',
                'label'    => __('Account Digit'),
                'title'    => __('Account Digit'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset_settings', ['legend' => __('Marketplace Settings')]);

        $fieldset->addField(
            'payment_plan',
            'text',
            [
                'name'     => 'payment_plan',
                'label'    => __('Payment Plan'),
                'title'    => __('Payment Plan'),
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'accepted_contract',
            'select',
            [
                'name'     => 'type',
                'label'    => __('Accepted Contract'),
                'title'    => __('Accepted Contract'),
                'values'   => $yesNo,
                'value'    => isset($formData['accepted_contract']) ? $formData['accepted_contract'] : 1,
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'marketplace_store',
            'select',
            [
                'name'     => 'marketplace_store',
                'label'    => __('Marketplace Store'),
                'title'    => __('Marketplace Store'),
                'values'   => $yesNo,
                'value'    => isset($formData['marketplace_store']) ? $formData['marketplace_store'] : 0,
                'class'    => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset_additional_data',
            [
                'legend' => $this->getFormLabelsBySellerType($subSellerType, 'base_fieldset_additional_data'),
            ]
        );

        $fieldset->addField(
            'occupation',
            'text',
            [
                'name'  => 'occupation',
                'label' => __('Occupation'),
                'title' => __('Occupation'),
            ]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset_gross',
            [
                'legend' => $this->getFormLabelsBySellerType($subSellerType, 'base_fieldset_gross'),
            ]
        );

        $fieldset->addField(
            'monthly_gross_income',
            'text',
            [
                'name'  => 'monthly_gross_income',
                'label' => __('Monthly Gross Income'),
                'title' => __('Monthly Gross Income'),
            ]
        );

        $fieldset->addField(
            'gross_equity',
            'text',
            [
                'name'  => 'gross_equity',
                'label' => __('Gross Equity'),
                'title' => __('Gross Equity'),
            ]
        );

        $form->setAction($this->getUrl('subseller/subseller/save'));
        $form->setUseContainer(true);
        $form->setId(self::FORM_ELEMENT_ID);
        $form->setMethod('post');

        $form->setValues($formData);
        $this->setForm($form);

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                \Magento\Framework\View\Element\Template::class
            )->setTemplate('Getnet_SubSellerMagento::sub_seller/js.phtml')
        );

        return parent::_prepareForm();
    }

    /**
     * Get Form Labels by Seller Type.
     *
     * @param int    $type
     * @param string $field
     *
     * @return \Magento\Framework\Phrase
     */
    public function getFormLabelsBySellerType(int $type, string $field): \Magento\Framework\Phrase
    {
        if ($type === 0) {
            $label = [
                'base_fieldset_addresses'       => __('Addresses'),
                'base_fieldset_additional_data' => __('Additional data'),
                'base_fieldset_document'        => __('Identification Document'),
                'base_fieldset_gross'           => __('Gross'),
            ];

            return $label[$field];
        }

        if ($type === 1) {
            $label = [
                'base_fieldset_addresses'       => __('Company Addresses'),
                'base_fieldset_additional_data' => __('Legal Data Representative'),
                'base_fieldset_document'        => __('Legal Representative Document\'s'),
                'base_fieldset_gross'           => __('Gross'),
            ];

            return $label[$field];
        }
    }
}
