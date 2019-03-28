<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\TokenParser;

use OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IncludeDynamicTokenParser;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Parser;
use Twig\Source;

/**
 * Class IncludeDynamicTokenParserTest
 */
class IncludeDynamicTokenParserTest extends TestCase
{

    /** @var Environment */
    private $environment;

    /** @var Parser */
    private $parser;

    /** @var IncludeDynamicTokenParser */
    private $includeDynamicTokenParser;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        /** @var LoaderInterface $loader */
        $loader = $this->getMockBuilder('Twig_LoaderInterface')->getMock();
        $this->environment = new Environment($loader, ['cache' => false]);

        $this->includeDynamicTokenParser = new IncludeDynamicTokenParser();
        $this->environment->addTokenParser($this->includeDynamicTokenParser);

        $this->parser = new Parser($this->environment);
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IncludeDynamicTokenParser::getTag
     */
    public function testGetTag(): void
    {
        $this->assertEquals('include_dynamic', $this->includeDynamicTokenParser->getTag());
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IncludeDynamicTokenParser::parse
     */
    public function testParse(): void
    {
        $source = "{% include_dynamic 'template.html.twig' with { varA: 'valueA' } %}";

        $stream = $this->environment->tokenize(new Source($source, 'index'));
        $node = $this->parser->parse($stream);

        $this->assertTrue($node->hasNode('body'));

        $bodyNode = $node->getNode('body');

        $includeDynamicNode = $bodyNode->getIterator()[0];

        $this->assertTrue($includeDynamicNode->hasNode('expr'));
        $this->assertTrue($includeDynamicNode->hasNode('variables'));
    }
}
