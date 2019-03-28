<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\TranslateFunctionLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\TranslateExtension;

/**
 * Class TranslateExtensionTest
 */
class TranslateExtensionTest extends AbstractExtensionTest
{

    /** @var TranslateExtension */
    protected $extension;

    protected $functions = ['translate'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $translateFunctionLogic = new TranslateFunctionLogic();
        $this->extension = new TranslateExtension($translateFunctionLogic);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [[], 'ERROR: Translation for IDENT MISSING not found!'],
            [['ident' => 'foobar'], 'ERROR: Translation for foobar not found!'],
            [['ident' => 'FIRST_NAME', 'suffix' => '_foo'], 'Vorname_foo'],
            [['ident' => 'foo', 'noerror' => true], 'foo'],
            [['ident' => 'foo', 'noerror' => 'bar'], 'foo']
        ];
    }

    /**
     * @param array  $params
     * @param string $expectedTranslation
     *
     * @dataProvider dataProvider
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\TranslateExtension::translate
     */
    public function testTranslate(array $params, string $expectedTranslation): void
    {
        $actualTranslation = $this->extension->translate($params);
        $this->assertEquals($actualTranslation, $expectedTranslation);
    }
}
