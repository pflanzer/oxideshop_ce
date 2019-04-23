<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\HasRightsExtension;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\HasRightsExtension;
use OxidEsales\EshopCommunity\Internal\Twig\Node\HasRightsNode;
use OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser;
use PHPUnit\Framework\TestCase;
use Twig\Compiler;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;
use Twig\Parser;
use Twig\Source;
use Twig\Token;

/**
 * Class HasRightsTokenParserTest
 */
class HasRightsTokenParserTest extends TestCase
{

    /**
     * @var HasRightsTokenParser
     */
    private $hasRightsParser;

    protected function setUp()
    {
        $env = $this->getEnv();
        $parser = new Parser($env);
        $this->hasRightsParser = new HasRightsTokenParser(HasRightsNode::class);
        $this->hasRightsParser->setParser($parser);
        parent::setUp();
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser::getTag
     */
    public function testGetTag()
    {
        $this->assertEquals('hasrights', $this->hasRightsParser->getTag());
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser::decideMyTagFork
     */
    public function testDecideMyTagForkIncorrect()
    {
        $token = new Token(Token::TEXT_TYPE, 1, 1);
        $this->assertEquals(false, $this->hasRightsParser->decideMyTagFork($token));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser::decideMyTagFork
     */
    public function testDecideMyTagForkCorrect()
    {
        $token = new Token(5, 'endhasrights', 1);
        $this->assertEquals(true, $this->hasRightsParser->decideMyTagFork($token));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser::parse
     */
    public function testParse()
    {
        /**
         * @var \Twig_LoaderInterface $loader
         */
        $loader = $this->getMockBuilder('Twig_LoaderInterface')->getMock();
        $env = new Environment($loader, array('cache' => false, 'autoescape' => false));
        $env->addExtension(new HasRightsExtension(new HasRightsTokenParser(HasRightsNode::class)));

        $stream = $env->parse($env->tokenize(new Source('{% hasrights {\'id\' : \'1\'} %}{% endhasrights %}', 'index')));
        $stream->compile(new Compiler($env));

        $tokens = $env->getTags();
        $extensions = $env->getExtensions();

        $this->assertTrue(isset($tokens['hasrights']));
        $this->assertTrue(isset($extensions['OxidEsales\EshopCommunity\Internal\Twig\Extensions\HasRightsExtension']));
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser::parse
     * @expectedException \Twig_Error_Syntax
     */
    public function testParseException()
    {
        /**
         * @var LoaderInterface $loader
         */
        $loader = $this->getMockBuilder('Twig_LoaderInterface')->getMock();
        $env = new Environment($loader, array('cache' => false, 'autoescape' => false));
        $env->addExtension(new HasRightsExtension(new HasRightsTokenParser(HasRightsNode::class)));

        $stream = $env->parse($env->tokenize(new Source('{% hasrights {\'id\' : \'1\'} %}{% foo %}', 'index')));
        $stream->compile(new Compiler($env));

        $this->expectExceptionMessage('Twig_Error_Syntax : Unexpected "foo" tag (expecting closing tag for the "hasrights" tag defined near line 1) in "index" at line 1.');
    }

    /**
     * @return Environment
     */
    private function getEnv(): Environment
    {
        $loader = new ArrayLoader(['tokens' => 'foo']);
        $env = new Environment($loader, ['debug' => false, 'cache' => false]);
        if (!$env->hasExtension('hasrights')) {
            $env->addExtension(new HasRightsExtension(new HasRightsTokenParser(HasRightsNode::class)));
            $env->addTokenParser(new HasRightsTokenParser(HasRightsNode::class));
        }

        return $env;
    }
}