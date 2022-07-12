<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Controller\Adminhtml\Subseller;

use Getnet\SubSellerMagento\Controller\RegistryConstants;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Edit extends \Getnet\SubSellerMagento\Controller\Adminhtml\Subseller
{
    /**
     * Show Edit Form.
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $subSellerId = (int) $this->getRequest()->getParam('subseller');

        $this->_coreRegistry->register(RegistryConstants::CURRENT_SUB_SELLER_ID, $subSellerId);

        try {
            $subSellerDataObject = $this->_subSellerRepository->get($subSellerId);
        } catch (NoSuchEntityException $e) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            return $resultRedirect->setPath('*/*/');
        }

        $resultPage = $this->initResultPage();
        $layout = $resultPage->getLayout();

        $toolbarSaveBlock = $layout->createBlock(\Getnet\SubSellerMagento\Block\Adminhtml\SubSeller\Toolbar\Save::class)
            ->assign('header', __('Edit Sub Seller'))
            ->assign(
                'form',
                $layout->createBlock(
                    \Getnet\SubSellerMagento\Block\Adminhtml\SubSeller\Form::class,
                    'subSeller_form'
                )->setShowLegend(true)
            );

        $resultPage->addBreadcrumb(
            __('Manage Sub Sellers'),
            __('Manage Sub Sellers'),
            $this->getUrl('subsller/subseller')
        )->addBreadcrumb(__('Edit Sub Seller'), __('Edit Sub Seller'))
        ->addContent($toolbarSaveBlock);

        $resultPage->getConfig()->getTitle()->prepend(__('Getnet Sub Sellers'));
        $resultPage->getConfig()->getTitle()->prepend(__('Sub Seller Code: %1', $subSellerDataObject->getCode()));

        return $resultPage;
    }
}
