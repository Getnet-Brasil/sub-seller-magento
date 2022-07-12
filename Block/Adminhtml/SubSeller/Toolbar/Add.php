<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

/**
 * Admin sub seller class toolbar.
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */

namespace Getnet\SubSellerMagento\Block\Adminhtml\SubSeller\Toolbar;

/**
 * @api
 *
 * @since 100.0.2
 */
class Add extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\ContainerInterface
{
    /**
     * @var string
     */
    protected $_template = 'Getnet_SubSellerMagento::toolbar/sub_seller/add.phtml';

    /**
     * @var \Magento\Backend\Block\Widget\Button\ButtonList
     */
    protected $buttonList;

    /**
     * @var \Magento\Backend\Block\Widget\Button\SplitButton
     */
    protected $splitButton;

    /**
     * @var \Magento\Backend\Block\Widget\Button\ToolbarInterface
     */
    protected $toolbar;

    /**
     * @param \Magento\Backend\Block\Template\Context               $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList       $buttonList
     * @param \Magento\Backend\Block\Widget\Button\SplitButton      $splitButton
     * @param \Magento\Backend\Block\Widget\Button\ToolbarInterface $toolbar
     * @param array                                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList,
        \Magento\Backend\Block\Widget\Button\SplitButton $splitButton,
        \Magento\Backend\Block\Widget\Button\ToolbarInterface $toolbar,
        array $data = []
    ) {
        $this->buttonList = $buttonList;
        $this->splitButton = $splitButton;
        $this->toolbar = $toolbar;
        parent::__construct($context, $data);
    }

    /**
     * @inheritdoc
     */
    public function addButton($buttonId, $data, $level = 0, $sortOrder = 0, $region = 'toolbar')
    {
        $this->buttonList->add($buttonId, $data, $level, $sortOrder, $region);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeButton($buttonId)
    {
        $this->buttonList->remove($buttonId);

        return $this;
    }

    /**
     * Prepare Layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->buttonList->add(
            'add',
            [
                'class_name' => \Magento\Backend\Block\Widget\Button\SplitButton::class,
                'label'      => __('Add New Sub Seller'),
                'onclick'    => 'window.location.href=\''.$this->getUrl('subseller/subseller/add').'\'',
                'class'      => 'add primary add-sub-seller',
                'options'    => $this->newSellerType(),
            ]
        );

        $this->toolbar->pushButtons($this, $this->buttonList);

        return parent::_prepareLayout();
    }

    /**
     * @inheritdoc
     */
    public function updateButton($buttonId, $key, $data)
    {
        $this->buttonList->update($buttonId, $key, $data);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function canRender(\Magento\Backend\Block\Widget\Button\Item $item)
    {
        return !$item->isDeleted();
    }

    /**
     * Retrieve options for newSellerType split button.
     *
     * @return array
     */
    protected function newSellerType()
    {
        $splitButtonOptions = [
            'pf' => [
                'label'   => __('Pessoa Física'),
                'onclick' => "setLocation('".$this->getUrl('subseller/subseller/add/type/0')."')",
                'default' => false,
            ],
            'pj' => [
                'label'   => __('Pessoa Jurídica'),
                'onclick' => "setLocation('".$this->getUrl('subseller/subseller/add/type/1')."')",
                'default' => false,
            ],
        ];

        return $splitButtonOptions;
    }
}
