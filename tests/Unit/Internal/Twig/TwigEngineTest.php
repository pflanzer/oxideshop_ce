<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig;

use org\bovigo\vfs\vfsStream;
use OxidEsales\EshopCommunity\Internal\Twig\Escaper\MailEscaper;
use OxidEsales\EshopCommunity\Internal\Twig\TwigEngine;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigEngineTest
 */
class TwigEngineTest extends TestCase
{

    private $templateDir;
    private $templateDirPath;
    private $template;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $templateDir = vfsStream::setup($this->getTemplateDir());
        $this->template = vfsStream::newFile($this->getTemplateName())->at($templateDir)->setContent("{{ 'twig' }}")->url();
        $this->templateDir = $templateDir;
        $this->templateDirPath = vfsStream::url($this->getTemplateDir());
    }

    public function test__construct(): void
    {
        $environment = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $environment->method('isDebug')->willReturn(true);
        $environment->expects($this->once())->method('addExtension');

        /** @var Environment $environment */

        new TwigEngine($environment);
    }

    public function testGetDefaultFileExtension(): void
    {
        $this->assertEquals('html.twig', $this->getEngine()->getDefaultFileExtension());
    }

    public function testExists(): void
    {
        $engine = $this->getEngine();
        $this->assertTrue($engine->exists($this->getTemplateName()));
        $this->assertFalse($engine->exists('foo'));
    }

    public function testSetCacheId(): void
    {
        $this->assertNull($this->getEngine()->setCacheId('cache'));
    }

    public function testAddGlobal(): void
    {
        $engine = $this->getEngine();
        $engine->addGlobal('foo', 'bar');
        $this->assertEquals(['foo' => 'bar'], $engine->getGlobals());
        $this->assertNotEquals(['not_foo' => 'not_bar'], $engine->getGlobals());
    }

    public function testRender(): void
    {
        $engine = $this->getEngine();
        $this->assertTrue(file_exists($this->template));
        $rendered = $engine->render($this->getTemplateName());
        $this->assertEquals('twig', $rendered);
        $this->assertNotEquals('foo', $rendered);
    }

    public function testAddEscaper(): void
    {
        $escaper = new MailEscaper();

        $coreExtension = $this
            ->getMockBuilder(AbstractExtension::class)
            ->setMethods(['setEscaper'])
            ->getMockForAbstractClass();

        $coreExtension
            ->expects($this->once())
            ->method('setEscaper')
            ->with($escaper->getStrategy(), [$escaper, 'escape']);

        $environment = $this
            ->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $environment
            ->method('getExtension')
            ->willReturn($coreExtension);

        /** @var Environment $environment */

        $twigEngine = new TwigEngine($environment);
        $twigEngine->addEscaper($escaper);
    }

    /**
     * @return TwigEngine
     */
    private function getEngine(): TwigEngine
    {
        $configuration = $this->getMockBuilder('OxidEsales\EshopCommunity\Internal\Twig\TemplateEngineConfigurationInterface')->getMock();
        $configuration->method('getParameters')->willReturn(['template_dir' => [$this->templateDirPath], 'is_debug' => 'false', 'cache_dir' => 'foo']);

        $loader = new FilesystemLoader($this->templateDirPath);

        $engine = new Environment($loader);

        return new TwigEngine($engine);
    }

    /**
     * @return string
     */
    private function getTemplateName(): string
    {
        return 'testTwigTemplate.twig';
    }

    /**
     * @return string
     */
    private function getTemplateDir(): string
    {
        return 'testTemplateDir';
    }

}
