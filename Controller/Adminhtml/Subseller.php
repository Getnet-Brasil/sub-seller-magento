<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Controller\Adminhtml;

use Magento\Framework\Controller\ResultFactory;

/**
 * Adminhtml sub seller controller.
 */
abstract class Subseller extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session.
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Getnet_SubSellerMagento::manage_subseller';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface
     */
    protected $_subSellerRepository;

    /**
     * @var \Getnet\SubSellerMagento\Model\Seller\Converter
     */
    protected $subSellerConverter;

    /**
     * @param \Magento\Backend\App\Action\Context                       $context
     * @param \Magento\Framework\Registry                               $coreRegistry
     * @param \Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface $subSellerRepository
     * @param \Getnet\SubSellerMagento\Model\Seller\Converter           $subSellerConverter
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Getnet\SubSellerMagento\Api\SubSellerRepositoryInterface $subSellerRepository,
        \Getnet\SubSellerMagento\Model\Seller\Converter $subSellerConverter
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_subSellerRepository = $subSellerRepository;
        $this->subSellerConverter = $subSellerConverter;
        parent::__construct($context);
    }

    /**
     * Validate/Filter Sub Seller Data.
     *
     * @param array $subSellerData
     *
     * @return array
     */
    protected function _processSubSellerData($subSellerData)
    {
        $result = [];
        foreach ($subSellerData as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->_processSubSellerData($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Initialize action.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initResultPage()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Getnet_SubSellerMagento::subseller_manager')
            ->addBreadcrumb(__('Getnet'), __('Getnet'))
            ->addBreadcrumb(__('SubSeller'), __('Sub Seller'));

        return $resultPage;
    }
}
