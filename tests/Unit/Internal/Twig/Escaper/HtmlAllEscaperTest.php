<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Escaper;

use OxidEsales\EshopCommunity\Internal\Twig\Escaper\EscaperInterface;
use OxidEsales\EshopCommunity\Internal\Twig\Escaper\HtmlAllEscaper;
use OxidEsales\TestingLibrary\UnitTestCase;
use Twig\Environment;

/**
 * Class HtmlAllEscaperTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class HtmlAllEscaperTest extends UnitTestCase
{

    /** @var EscaperInterface */
    private $escaper;

    /** @var Environment */
    private $environment;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->escaper = new HtmlAllEscaper();
        $this->environment = $this->createMock(Environment::class);
    }

    /**
     * @return array
     */
    public function escapeProvider(): array
    {
        return [
            ["A 'quote' is <b>bold</b>", "A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt;"]
        ];
    }

    /**
     * @param string $string
     * @param string $expected
     *
     * @dataProvider escapeProvider
     */
    public function testEscape(string $string, string $expected): void
    {
        $this->assertEquals($expected, $this->escaper->escape($this->environment, $string, 'UTF-8'));
    }

    public function testGetStrategy(): void
    {
        $this->assertEquals('htmlall', $this->escaper->getStrategy());
    }
}