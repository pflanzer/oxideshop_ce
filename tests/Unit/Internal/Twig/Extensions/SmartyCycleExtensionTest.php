<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\SmartyCycleExtension;

/**
 * Class SmartyCycleExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class SmartyCycleExtensionTest extends AbstractExtensionTest
{

    /** @var SmartyCycleExtension */
    protected $extension;

    protected $functions = ['smarty_cycle'];

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new SmartyCycleExtension();
    }

    /**
     * @param string $template
     * @param string $expected
     * @param array  $variables
     *
     * @dataProvider getSmartyCycle
     */
    public function testSmartyCycle(string $template, string $expected, array $variables = []): void
    {
        $this->assertEquals($expected, $this->getTemplate($template)->render($variables));
    }

    /**
     * @return array
     */
    public function getSmartyCycle(): array
    {
        return [
            [
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values) }}",
                "aba",
                ['values' => ["a", "b"]]
            ],
            [
                "{{ smarty_cycle(values, { name: \"cycleName\" }) }}" .
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values, { name: \"cycleName\" }) }}",
                "aab",
                ['values' => ["a", "b"]]
            ],
            [
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values, { reset: true }) }}" .
                "{{ smarty_cycle(values) }}",
                "aab",
                ['values' => ["a", "b"]]
            ],
            [
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle() }}" .
                "{{ smarty_cycle() }}",
                "aba",
                ['values' => ["a", "b"]]
            ],
            [
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values, { advance: false }) }}" .
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values) }}",
                "abba",
                ['values' => ["a", "b"]]
            ],
            [
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values, { print: false }) }}" .
                "{{ smarty_cycle(values) }}" .
                "{{ smarty_cycle(values) }}",
                "aab",
                ['values' => ["a", "b"]]
            ],
            [
                "{{ smarty_cycle(valuesA) }}" .
                "{{ smarty_cycle(valuesB) }}",
                "ac",
                ['valuesA' => ["a", "b"], 'valuesB' => ["c", "d"]]
            ],
            [
                "{{ smarty_cycle(values, { delimiter: ':' }) }}" .
                "{{ smarty_cycle() }}",
                "ab",
                ['values' => 'a:b']
            ]
        ];
    }

    /**
     * @expectedException Twig\Error\Error
     * @expectedExceptionMessage static_cycle: missing 'values' parameter
     */
    public function testSmartyCycleWithMissingValuesParameter(): void
    {
        $template = "{{ smarty_cycle() }}";

        $this->getTemplate($template)->render([]);
    }
}
