<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\TokenParser;

use OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IfContentTokenParser;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Node\Node;
use Twig\Parser;
use Twig\Source;
use Twig\Token;

/**
 * Class IfContentTokenParserTest
 */
class IfContentTokenParserTest extends TestCase
{

    /** @var Environment */
    private $environment;

    /** @var Parser */
    private $parser;

    /** @var IfContentTokenParser */
    private $ifContentParser;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        /** @var LoaderInterface $loader */
        $loader = $this->getMockBuilder('Twig_LoaderInterface')->getMock();
        $this->environment = new \Twig_Environment($loader, ['cache' => false]);

        $this->ifContentParser = new IfContentTokenParser();
        $this->environment->addTokenParser($this->ifContentParser);

        $this->parser = new Parser($this->environment);
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IfContentTokenParser::getTag
     */
    public function testGetTag(): void
    {
        $this->assertEquals('ifcontent', $this->ifContentParser->getTag());
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IfContentTokenParser::parse
     */
    public function testParseIdent(): void
    {
        $source = "{% ifcontent ident \"oxsomething\" set myVar %}Lorem Ipsum{% endifcontent %}";

        $stream = $this->environment->tokenize(new Source($source, 'index'));
        $node = $this->parser->parse($stream);

        $this->assertTrue($node->hasNode('body'));

        $bodyNode = $node->getNode('body');

        /** @var Node $ifContentNode */
        $ifContentNode = $bodyNode->getIterator()[0];

        $this->assertTrue($ifContentNode->hasNode('body'));
        $this->assertTrue($ifContentNode->hasNode('variable'));
        $this->assertTrue($ifContentNode->hasNode('ident'));
        $this->assertFalse($ifContentNode->hasNode('oxid'));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IfContentTokenParser::parse
     */
    public function testParseOxid(): void
    {
        $source = "{% ifcontent oxid \"oxsomething\" set myVar %}Lorem Ipsum{% endifcontent %}";

        $stream = $this->environment->tokenize(new Source($source, 'index'));
        $node = $this->parser->parse($stream);

        $this->assertTrue($node->hasNode('body'));

        $bodyNode = $node->getNode('body');

        /** @var Node $ifContentNode */
        $ifContentNode = $bodyNode->getIterator()[0];

        $this->assertTrue($ifContentNode->hasNode('body'));
        $this->assertTrue($ifContentNode->hasNode('variable'));
        $this->assertFalse($ifContentNode->hasNode('ident'));
        $this->assertTrue($ifContentNode->hasNode('oxid'));
    }

    /**
     * @expectedException  Twig\Error\SyntaxError
     * @expectedExceptionMessage No Ident nor Oxid provided for ifcontent
     */
    public function testParseNoIdentAndOxid()
    {
        $source = "{% ifcontent set myVar %}Lorem Ipsum{% endifcontent %}";
        $stream = $this->environment->tokenize(new Source($source, 'index'));
        $this->parser->parse($stream);
    }

    public function testParseNoVar()
    {
        $source = "{% ifcontent ident \"oxsomething\" %}Lorem Ipsum{% endifcontent %}";

        $stream = $this->environment->tokenize(new Source($source, 'index'));
        $node = $this->parser->parse($stream);

        $this->assertTrue($node->hasNode('body'));

        $bodyNode = $node->getNode('body');

        /** @var Node $ifContentNode */
        $ifContentNode = $bodyNode->getIterator()[0];

        $this->assertTrue($ifContentNode->hasNode('body'));
        $this->assertTrue($ifContentNode->hasNode('variable'));
        $this->assertTrue($ifContentNode->hasNode('ident'));

        $this->assertEquals('oCont', $ifContentNode->getNode('variable')->getAttribute('name'));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\IfContentTokenParser::decideBlockEnd
     */
    public function testDecideBlockEnd(): void
    {
        $token = new Token(Token::NAME_TYPE, 'foo', 1);
        $this->assertEquals(false, $this->ifContentParser->decideBlockEnd($token));

        $token = new Token(Token::NAME_TYPE, 'endifcontent', 1);
        $this->assertEquals(true, $this->ifContentParser->decideBlockEnd($token));
    }
}
