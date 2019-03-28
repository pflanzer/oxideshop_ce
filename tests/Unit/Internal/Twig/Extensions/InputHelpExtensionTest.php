<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\InputHelpLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\InputHelpExtension;

/**
 * Class InputHelpExtensionTest
 */
class InputHelpExtensionTest extends AbstractExtensionTest
{

    /** @var InputHelpExtension */
    protected $extension;

    protected $functions = ['help_id', 'help_text'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $inputHelpLogic = new InputHelpLogic();
        $this->extension = new InputHelpExtension($inputHelpLogic);
    }

    /**
     * @return array
     */
    public function getIdentProvider(): array
    {
        return array(
            [[], 1, false, null],
            [['ident' => 'FIRST_NAME'], 1, false, 'FIRST_NAME']
        );
    }

    /**
     * @param array  $params
     * @param int    $iLang
     * @param bool   $blAdmin
     * @param string $expected
     *
     * @dataProvider getIdentProvider
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\InputHelpExtension::getHelpId
     */
    public function testGetIdent(array $params, int $iLang, bool $blAdmin, string $expected = null): void
    {
        $this->setLanguage($iLang);
        $this->setAdminMode($blAdmin);
        $this->assertEquals($expected, $this->extension->getHelpId($params));
    }

    /**
     * @return array
     */
    public function getHelpTextProvider(): array
    {
        return array(
            [[], 1, false, null],
            [['ident' => 'FIRST_NAME'], 1, false, 'First name'],
            [['ident' => 'FIRST_NAME'], 0, false, 'Vorname'],
            [['ident' => 'GENERAL_SAVE'], 1, true, 'Save'],
            [['ident' => 'GENERAL_SAVE'], 0, true, 'Speichern'],
            [['ident' => 'VAT'], 1, false, 'VAT'],
        );
    }

    /**
     * @param array  $params
     * @param int    $iLang
     * @param bool   $blAdmin
     * @param string $expected
     *
     * @dataProvider getHelpTextProvider
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\InputHelpExtension::getHelpText
     */
    public function testGetHelpText(array $params, int $iLang, bool $blAdmin, string $expected = null): void
    {
        $this->setLanguage($iLang);
        $this->setAdminMode($blAdmin);
        $this->assertEquals($expected, $this->extension->getHelpText($params));
    }

}
