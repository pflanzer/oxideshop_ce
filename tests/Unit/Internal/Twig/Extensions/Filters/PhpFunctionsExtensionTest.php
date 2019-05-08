<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\PhpFunctionsExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

class PhpFunctionsExtensionTest extends AbstractExtensionTest
{

    /** @var PhpFunctionsExtension */
    protected $extension;

    protected $filters = ['parse_url', 'oxNew', 'strtotime', 'is_array', 'urlencode', 'addslashes', 'getimagesize'];

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->extension = new PhpFunctionsExtension();
    }

    /**
     * @return array
     */
    public function dummyTemplateProvider(): array
    {
        return [
            ["{% set foo = 'bar'|parse_url %}{{ foo['path'] }}", 'bar'],
            ["{{ 'Mon, 21 Jan 2019 15:35:00 GMT'|strtotime }}", 1548084900],
            ["{{ {0:0, 1:1}|is_array  }}", true],
            ["{{ 'foo'|is_array  }}", false],
            ["{{ 'discount_categories_ajax'|oxNew is null  }}", false]
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
