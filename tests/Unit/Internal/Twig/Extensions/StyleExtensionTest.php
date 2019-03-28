<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\StyleLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\StyleExtension;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

/**
 * Class StyleExtensionTest
 */
class StyleExtensionTest extends AbstractExtensionTest
{

    /** @var StyleExtension */
    protected $extension;

    protected $functions = ['style'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new StyleExtension(new StyleLogic());
    }

    /**
     * @covers       \OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\StyleLogic::collectStyleSheets
     * @dataProvider dataProvider
     *
     * @param array $params
     * @param bool $isDynamic
     */
    public function testCollectStyleSheets(array $params, bool $isDynamic): void
    {
        $styleExtension = $this->getStyleExtensionMock($params, $isDynamic);
        $env = $this->getTwigEnvironment($isDynamic);
        $styleExtension->style($env, $params);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [['foo' => 'bar', '__oxid_include_dynamic' => true], true],
            [['foo' => 'bar', '__oxid_include_dynamic' => false], false],
            [['foo' => 'bar'], false]
        ];
    }

    /**
     * @param bool $isDynamic
     *
     * @return Environment
     */
    private function getTwigEnvironment(bool $isDynamic): Environment
    {
        /** @var LoaderInterface $loader */
        $loader = $this->getMockBuilder(LoaderInterface::class)->getMock();
        $env = new Environment($loader, []);
        $env->addGlobal('__oxid_include_dynamic', $isDynamic);
        return $env;
    }

    /**
     * @param array $params
     * @param bool  $isDynamic
     *
     * @return StyleExtension
     */
    private function getStyleExtensionMock(array $params, bool $isDynamic): StyleExtension
    {
        $styleLogic = $this->getMockBuilder(StyleLogic::class)->disableOriginalConstructor()->getMock();
        $styleLogic->method('collectStyleSheets')->willReturn([]);
        $styleLogic->expects($this->once())->method('collectStyleSheets')->with($params, $isDynamic);

        /** @var StyleLogic $styleLogic */

        return new StyleExtension($styleLogic);
    }
}
