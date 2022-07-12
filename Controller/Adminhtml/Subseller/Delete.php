<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Controller\Adminhtml\Subseller;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends \Getnet\SubSellerMagento\Controller\Adminhtml\Subseller implements HttpPostActionInterface
{
    /**
     * Delete Rate and Data.
     *
     * @return \Magento\Backend\Model\View\Result\Redirect|void
     */
    public function execute()
    {
        if ($subSellerId = $this->getRequest()->getParam('subseller')) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

            try {
                $this->_subSellerRepository->deleteById($subSellerId);

                $this->messageManager->addSuccess(__('You deleted the sub seller.'));

                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addError(
                    __('We can\'t delete this  sub seller because of an incorrect sub seller ID.')
                );

                return $resultRedirect->setPath('subseller/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Something went wrong deleting this  sub seller.'));
            }

            if ($this->getRequest()->getServer('HTTP_REFERER')) {
                $resultRedirect->setRefererUrl();
            } else {
                $resultRedirect->setPath('*/*/');
            }

            return $resultRedirect;
        }
    }
}
