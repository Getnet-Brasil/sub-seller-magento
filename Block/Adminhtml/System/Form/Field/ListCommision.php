<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

namespace Getnet\SubSellerMagento\Block\Adminhtml\System\Form\Field;

use Getnet\SubSellerMagento\Block\Adminhtml\System\Form\Field\Column\BrandColumn;
use Getnet\SubSellerMagento\Block\Adminhtml\System\Form\Field\Column\ProductColumn;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ListCommision - Add List Commision to field.
 */
class ListCommision extends AbstractFieldArray
{
    /**
     * @var BrandColumn
     */
    private $brandRenderer;

    /**
     * @var ProductColumn
     */
    private $productRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns.
     */
    protected function _prepareToRender()
    {
        $this->addColumn('brand', [
            'label'    => __('Brand'),
            'renderer' => $this->getFieldBrandRenderer(),
        ]);

        $this->addColumn('product', [
            'label'    => __('Product'),
            'renderer' => $this->getFieldProductRenderer(),
        ]);

        $this->addColumn('payment_plan', [
            'label' => __('Payment Plan'),
            'class' => 'required-entry',
        ]);

        $this->addColumn('commission_percentage', [
            'label' => __('Commission Percentage'),
            'class' => 'required-entry',
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object.
     *
     * @param DataObject $row
     *
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $field = $row->getField();
        if ($field !== null) {
            $options['option_'.$this->getFieldBrandRenderer()->calcOptionHash($field)] = 'selected="selected"';
            $options['option_'.$this->getFieldProductRenderer()->calcOptionHash($field)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Create Block Brand Renderer.
     *
     * @throws LocalizedException
     *
     * @return BrandColumn
     */
    private function getFieldBrandRenderer()
    {
        if (!$this->brandRenderer) {
            $this->brandRenderer = $this->getLayout()->createBlock(
                BrandColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->brandRenderer;
    }

    /**
     * Create Block Product Renderer.
     *
     * @throws LocalizedException
     *
     * @return ProductColumn
     */
    private function getFieldProductRenderer()
    {
        if (!$this->productRenderer) {
            $this->productRenderer = $this->getLayout()->createBlock(
                ProductColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->productRenderer;
    }
}
