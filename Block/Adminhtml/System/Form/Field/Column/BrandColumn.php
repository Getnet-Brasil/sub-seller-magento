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
 * Class BrandColumn - Create Field to Brand Column.
 */
class BrandColumn extends Select
{
    public const MASTERCARD = 'MASTERCARD';

    public const VISA = 'VISA';

    public const AMEX = 'AMEX';

    public const ELO_CREDITO = 'ELO CRÉDITO';

    public const HIPERCARD = 'HIPERCARD';

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
            ['value' => self::MASTERCARD, 'label' => 'Mastercard'],
            ['value' => self::VISA, 'label' => 'Visa'],
            ['value' => self::AMEX, 'label' => 'American Express'],
            ['value' => self::ELO_CREDITO, 'label' => 'Elo'],
            ['value' => self::HIPERCARD, 'label' => 'Hipercard'],
            ['value' => self::BOLETO, 'label' => 'Boleto'],
        ];
    }
}
