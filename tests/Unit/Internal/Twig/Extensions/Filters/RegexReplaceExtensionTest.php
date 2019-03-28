<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\RegexReplaceExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

class RegexReplaceExtensionTest extends AbstractExtensionTest
{
    /** @var RegexReplaceExtension */
    protected $extension;

    protected $filters = ['regex_replace'];

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->extension = new RegexReplaceExtension();
    }

    /**
     * @return array
     */
    public function dummyTemplateProvider(): array
    {
        return [
            ["{{ '1-foo-2'|regex_replace('/[0-9]/', 'bar') }}", "bar-foo-bar"],
        ];
    }

    /**
     * @param string $template
     * @param string $expected
     *
     * @dataProvider dummyTemplateProvider
     */
    public function testIfPhpFunctionsAreCallable(string $template, string $expected): void
    {
        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }
}
