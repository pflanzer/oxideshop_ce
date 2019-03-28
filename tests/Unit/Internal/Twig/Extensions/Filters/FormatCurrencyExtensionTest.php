<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\FormatCurrencyLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatCurrencyExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class FormatCurrencyExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class FormatCurrencyExtensionTest extends AbstractExtensionTest
{

    /** @var FormatCurrencyExtension */
    protected $extension;

    protected $filters = ['format_currency'];

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->extension = new FormatCurrencyExtension(new FormatCurrencyLogic());
    }

    public function testNumberFormat(): void
    {
        $template = "{{ 'EUR@ 1.00@ .@ ,@ EUR@ 2'|format_currency(25000000.5584) }}";
        $expected = '25,000,000.56';

        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }
}
