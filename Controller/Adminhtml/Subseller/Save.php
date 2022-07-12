<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Controller\Adminhtml\Subseller;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Getnet\SubSellerMagento\Controller\Adminhtml\Subseller
{
    /**
     * Save Sub Seller and Data.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $subSellerPost = $this->getRequest()->getPostValue();
        if ($subSellerPost) {
            $sellerId = $this->getRequest()->getParam('sellerid');
            if ($sellerId) {
                try {
                    $this->_subSellerRepository->get($sellerId);
                } catch (NoSuchEntityException $e) {
                    unset($subSellerPost['sellerid']);
                }
            }

            try {
                $subSellerData = $this->subSellerConverter->populateSubSellerData($subSellerPost);
                $this->_subSellerRepository->save($subSellerData);

                $this->messageManager->addSuccess(__('You saved the sub seller.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->_objectManager->get(\Magento\Backend\Model\Session::class)->setFormData($subSellerPost);
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            return $resultRedirect->setUrl($this->_redirect->getRedirectUrl($this->getUrl('*')));
        }

        return $resultRedirect->setPath('subseller/subseller');
    }
}
