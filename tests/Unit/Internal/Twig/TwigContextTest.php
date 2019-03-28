<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\EshopCommunity\Internal\Twig\TwigContext;
use PHPUnit\Framework\TestCase;

/**
 * Class TwigContextTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class TwigContextTest extends TestCase
{

    /** @var TwigContext */
    private $twigContext;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->twigContext = new TwigContext(new Config(), new UtilsView());
    }

    public function testGetTemplateDirectories(): void
    {
        $templateDirectories = $this->twigContext->getTemplateDirectories();

        $this->assertEmpty($templateDirectories);
    }

    public function testGetIsDebugTrue(): void
    {
        $config = new Config();
        $config->setConfigParam('iDebug', true);

        $twigContext = new TwigContext($config, new UtilsView());
        $this->assertTrue($twigContext->getIsDebug());
    }

    public function testGetIsDebugFalse(): void
    {
        $config = new Config();
        $config->setConfigParam('iDebug', false);

        $twigContext = new TwigContext($config, new UtilsView());
        $this->assertFalse($twigContext->getIsDebug());
    }

    public function testGetCacheDir(): void
    {
        $config = new Config();
        $config->setConfigParam('sCompileDir', 'path/to/cache');

        $twigContext = new TwigContext($config, new UtilsView());
        $this->assertEquals('path/to/cache/twig', $twigContext->getCacheDir());
    }
}
