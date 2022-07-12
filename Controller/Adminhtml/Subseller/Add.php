<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Controller\Adminhtml\Subseller;

use Getnet\SubSellerMagento\Controller\RegistryConstants;

class Add extends \Getnet\SubSellerMagento\Controller\Adminhtml\Subseller
{
    /**
     * Show Add Form.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->_coreRegistry->register(
            RegistryConstants::CURRENT_SUB_SELLER_FORM_DATA,
            $this->_objectManager->get(\Magento\Backend\Model\Session::class)->getFormData(true)
        );
        $subSellerType = (int) $this->getRequest()->getParam('type');
        $this->_coreRegistry->register(RegistryConstants::CURRENT_SUB_SELLER_TYPE, $subSellerType);

        $resultPage = $this->initResultPage();
        $layout = $resultPage->getLayout();
        $toolbarSaveBlock = $layout->createBlock(\Getnet\SubSellerMagento\Block\Adminhtml\SubSeller\Toolbar\Save::class)
            ->assign('header', __('Add New Sub Seller'))
            ->assign(
                'form',
                $layout->createBlock(
                    \Getnet\SubSellerMagento\Block\Adminhtml\SubSeller\Form::class,
                    'sub_seller_form'
                )
            );

        $resultPage->addBreadcrumb(
            __('Manage Sub Seller'),
            __('Manage Sub Seller'),
            $this->getUrl('subseller/subseller')
        )
            ->addBreadcrumb(__('New Sub Seller'), __('New Sub Seller'))
            ->addContent($toolbarSaveBlock);

        $resultPage->getConfig()->getTitle()->prepend(__('Getnet'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Sub Seller'));

        return $resultPage;
    }
}
