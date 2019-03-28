<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\CatExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class CatExtensionTest
 */
class CatExtensionTest extends AbstractExtensionTest
{

    /** @var CatExtension */
    protected $extension;

    protected $filters = ['cat'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new CatExtension();
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\CatExtension::cat
     */
    public function testCat(): void
    {
        $string = 'foo';
        $cat = 'bar';
        $actual = $this->extension->cat($string, $cat);
        $this->assertEquals($string . $cat, $actual);
    }
}
