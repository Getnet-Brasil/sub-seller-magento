<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Controller\Adminhtml\Subseller;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends \Getnet\SubSellerMagento\Controller\Adminhtml\Subseller implements HttpGetActionInterface
{
    /**
     * Show Main Grid.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->initResultPage();
        $resultPage->addBreadcrumb(__('Manage Sub Sellers'), __('Manage Sub Sellers'));
        $resultPage->getConfig()->getTitle()->prepend(__('Sub Sellers'));

        return $resultPage;
    }
}
