<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\FormatDateLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatDateExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class FormatDateExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class FormatDateExtensionTest extends AbstractExtensionTest
{

    /** @var FormatDateExtension */
    protected $extension;

    protected $filters = ['format_date'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new FormatDateExtension(new FormatDateLogic());
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatDateExtension::formatDate
     */
    public function testFormDateWithDatetime(): void
    {
        $template = "{{ '01.08.2007 11.56.25'|format_date('datetime', true) }}";
        $expected = "2007-08-01 11:56:25";

        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatDateExtension::formatDate
     */
    public function testFormDateWithTimestamp(): void
    {
        $template = "{{ '20070801115625'|format_date('timestamp', true) }}";
        $expected = "2007-08-01 11:56:25";

        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatDateExtension::formatDate
     */
    public function testFormDateWithDate(): void
    {
        $template = "{{ '2007-08-01 11:56:25'|format_date('date', true) }}";
        $expected = "2007-08-01";

        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatDateExtension::formatDate
     */
    public function testFormDateUsingObject(): void
    {
        $template = "{{ field|format_date('datetime') }}";
        $expected = "2007-08-01 11:56:25";

        $field = new Field();
        $field->fldmax_length = "0";
        $field->fldtype = 'datetime';
        $field->setValue('01.08.2007 11.56.25');

        $this->assertEquals($expected, $this->getTemplate($template)->render(['field' => $field]));
    }
}
