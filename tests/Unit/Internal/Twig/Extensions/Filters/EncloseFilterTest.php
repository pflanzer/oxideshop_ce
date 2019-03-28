<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\EncloseExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class EncloseFilterTest
 */
class EncloseFilterTest extends AbstractExtensionTest
{

    /** @var EncloseExtension */
    protected $extension;

    protected $filters = ['enclose'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new EncloseExtension();
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\EncloseExtension::enclose
     */
    public function testEnclose(): void
    {
        $string = "foo";
        $encloser = "*";
        $enclosedString = $this->extension->enclose($string, $encloser);
        $this->assertEquals('*foo*', $enclosedString);
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\EncloseExtension::enclose
     */
    public function testEncloseNoEncloder(): void
    {
        $string = "foo";
        $enclosedString = $this->extension->enclose($string);
        $this->assertEquals('foo', $enclosedString);
    }
}
