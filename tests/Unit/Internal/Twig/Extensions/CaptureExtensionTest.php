<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\CaptureExtension;
use OxidEsales\EshopCommunity\Internal\Twig\TokenParser\CaptureTokenParser;

/**
 * Class CaptureExtensionTest
 */
class CaptureExtensionTest extends AbstractExtensionTest
{

    /** @var CaptureExtension */
    protected $extension;

    protected $tags = ['capture'];

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->extension = new CaptureExtension();
        parent::setUp();
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\CaptureExtension::getTokenParsers
     */
    public function testGetTokenParsers()
    {
        $tokenParser = $this->extension->getTokenParsers();
        $this->assertInstanceOf(CaptureTokenParser::class, $tokenParser[0]);
    }
}
