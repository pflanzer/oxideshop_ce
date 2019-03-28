<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\TestingLibrary\UnitTestCase;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\ArrayLoader;
use Twig\Template;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class AbstractExtensionTest
 */
abstract class AbstractExtensionTest extends UnitTestCase
{
    /** @var AbstractExtension */
    protected $extension;

    /** @var string[] */
    protected $filters = [];

    /** @var string[] */
    protected $functions = [];

    /** @var string[] */
    protected $tags = [];

    /** @var Environment */
    private $environment;


    /**
     * @return Environment
     */
    protected function getEnvironment(): Environment
    {
        if (!$this->environment) {
            $this->environment = new Environment(new ArrayLoader(), ['debug' => true, 'cache' => false]);
            $this->environment->addExtension($this->extension);
        }

        return $this->environment;
    }

    /**
     * @param string $template
     *
     * @return Template
     */
    protected function getTemplate(string $template): Template
    {
        $loader = new ArrayLoader(['index' => $template]);
        $environment = $this->getEnvironment();
        $environment->setLoader($loader);

        return $environment->loadTemplate('index');
    }

    /**
     * Tests if filters provided in $this->filters are registered by their names
     */
    public function testFilterNames(): void
    {
        $filters = array_map(
            function (TwigFilter $filter) {
                return $filter->getName();
            },
            $this->extension->getFilters()
        );

        $this->assertEquals($this->filters, $filters, "", 0.0, 10, true);
    }

    /**
     * Tests if functions provided in $this->functions are registered by their names
     */
    public function testFunctionNames(): void
    {
        $functions = array_map(
            function (TwigFunction $function) {
                return $function->getName();
            },
            $this->extension->getFunctions()
        );

        $this->assertEquals($this->functions, $functions, "", 0.0, 10, true);
    }

    /**
     * Tests if tags provided in $this->tags are registered by their names
     */
    public function testTags(): void
    {
        $tags = array_map(
            function (TokenParserInterface $tokenParser) {
                return $tokenParser->getTag();
            },
            $this->extension->getTokenParsers()
        );

        $this->assertEquals($this->tags, $tags, "", 0.0, 10, true);
    }
}
