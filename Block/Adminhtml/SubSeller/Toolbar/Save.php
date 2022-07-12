<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Block\Adminhtml\SubSeller\Toolbar;

/**
 * SubSeller toolbar block.
 */
class Save extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\ContainerInterface
{
    /**
     * @var string
     */
    protected $_template = 'Getnet_SubSellerMagento::toolbar/sub_seller/save.phtml';

    /**
     * @var \Magento\Backend\Block\Widget\Button\ButtonList
     */
    protected $buttonList;

    /**
     * @var \Magento\Backend\Block\Widget\Button\ToolbarInterface
     */
    protected $toolbar;

    /**
     * @param \Magento\Backend\Block\Template\Context               $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList       $buttonList
     * @param \Magento\Backend\Block\Widget\Button\ToolbarInterface $toolbar
     * @param array                                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList,
        \Magento\Backend\Block\Widget\Button\ToolbarInterface $toolbar,
        array $data = []
    ) {
        $this->buttonList = $buttonList;
        $this->toolbar = $toolbar;
        parent::__construct($context, $data);
    }

    /**
     * Init model.
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->assign('createUrl', $this->getUrl('subseller/subseller/save'));
    }

    /**
     * Public wrapper for the button list.
     *
     * @param string      $buttonId
     * @param array       $data
     * @param int         $level
     * @param int         $sortOrder
     * @param string|null $region    That button should be displayed in ('toolbar', 'header', 'footer', null)
     *
     * @return $this
     */
    public function addButton($buttonId, $data, $level = 0, $sortOrder = 0, $region = 'toolbar')
    {
        $this->buttonList->add($buttonId, $data, $level, $sortOrder, $region);

        return $this;
    }

    /**
     * Public wrapper for the button list.
     *
     * @param string $buttonId
     *
     * @return $this
     */
    public function removeButton($buttonId)
    {
        $this->buttonList->remove($buttonId);

        return $this;
    }

    /**
     * Public wrapper for protected _updateButton method.
     *
     * @param string      $buttonId
     * @param string|null $key
     * @param string      $data
     *
     * @return $this
     */
    public function updateButton($buttonId, $key, $data)
    {
        $this->buttonList->update($buttonId, $key, $data);

        return $this;
    }

    /**
     * Prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->buttonList->add(
            'back',
            [
                'label'   => __('Back'),
                'onclick' => 'window.location.href=\''.$this->getUrl('subseller/*/').'\'',
                'class'   => 'back',
            ]
        );

        $this->buttonList->add(
            'reset',
            ['label' => __('Reset'), 'onclick' => 'window.location.reload()', 'class' => 'reset']
        );

        $subSeller = (int) $this->getRequest()->getParam('subseller');
        if ($subSeller) {
            $this->buttonList->add(
                'delete',
                [
                    'label'   => __('Delete Sub Seller'),
                    'onclick' => 'deleteConfirm(\''.__(
                        'Are you sure you want to do this?'
                    ).'\', \''.$this->getUrl(
                        'subseller/*/delete',
                        ['subseller' => $subSeller]
                    ).'\', {data: {}})',
                    'class' => 'delete',
                ]
            );
        }

        $this->buttonList->add(
            'save',
            [
                'label'          => __('Save Sub Seller'),
                'class'          => 'save primary save-subseller',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#sub-seller-form']],
                ],
            ]
        );
        $this->toolbar->pushButtons($this, $this->buttonList);

        return parent::_prepareLayout();
    }

    /**
     * Check whether button rendering is allowed in current context.
     *
     * @param \Magento\Backend\Block\Widget\Button\Item $item
     *
     * @return bool
     */
    public function canRender(\Magento\Backend\Block\Widget\Button\Item $item)
    {
        return !$item->isDeleted();
    }
}
