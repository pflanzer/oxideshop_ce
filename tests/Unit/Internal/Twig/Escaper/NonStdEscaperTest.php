<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Escaper;

use OxidEsales\EshopCommunity\Internal\Twig\Escaper\EscaperInterface;
use OxidEsales\EshopCommunity\Internal\Twig\Escaper\NonStdEscaper;
use OxidEsales\TestingLibrary\UnitTestCase;
use Twig\Environment;

/**
 * Class NonStdEscaperTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class NonStdEscaperTest extends UnitTestCase
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
        $this->escaper = new NonStdEscaper();
        $this->environment = $this->createMock(Environment::class);
    }

    /**
     * @return array
     */
    public function escapeProvider(): array
    {
        return [
            [
                "Zażółć 'gęślą' <b>jaźń</b>",
                "Za&#197;&#188;&#195;&#179;&#197;&#130;&#196;&#135; 'g&#196;&#153;&#197;&#155;l&#196;&#133;' <b>ja&#197;&#186;&#197;&#132;</b>"
            ]
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
        $this->assertEquals('nonstd', $this->escaper->getStrategy());
    }
}