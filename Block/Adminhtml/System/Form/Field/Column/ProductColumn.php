<?php
/**
 * Copyright © Getnet. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Getnet\SubSellerMagento\Block\Adminhtml\System\Form\Field\Column;

use Magento\Framework\View\Element\Html\Select;

/**
 * Class ProductColumn - Create Field to Product Column.
 */
class ProductColumn extends Select
{
    public const CREDITO_1X = 'CREDITO A VISTA';

    public const CREDITO_PARCELADO_3X = 'PARCELADO LOJISTA 3X';

    public const CREDITO_PARCELADO_6X = 'PARCELADO LOJISTA 6X';

    public const CREDITO_PARCELADO_9X = 'PARCELADO LOJISTA 9X';

    public const CREDITO_PARCELADO_12X = 'PARCELADO LOJISTA 12X';

    public const BOLETO = 'BOLETO';

    /**
     * Set "name" for <select> element.
     *
     * @param string $value
     *
     * @return void
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element.
     *
     * @param string $value
     *
     * @return void
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML.
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }

        return parent::_toHtml();
    }

    /**
     * Get Options.
     *
     * @return array
     */
    public function getSourceOptions(): array
    {
        return [
            ['value' => self::CREDITO_1X, 'label' => 'Pagamento em crédito à vista'],
            ['value' => self::CREDITO_PARCELADO_3X, 'label' => 'Pagamento em crédito até 3 parcelas'],
            ['value' => self::CREDITO_PARCELADO_6X, 'label' => 'Pagamento em crédito até 6 parcelas'],
            ['value' => self::CREDITO_PARCELADO_9X, 'label' => 'Pagamento em crédito até 9 parcelas'],
            ['value' => self::CREDITO_PARCELADO_12X, 'label' => 'Pagamento em crédito até 12 parcelas'],
            ['value' => self::BOLETO, 'label' => 'Pagamento por Boleto'],
        ];
    }
}
