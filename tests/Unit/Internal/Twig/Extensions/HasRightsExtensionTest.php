<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\HasRightsExtension;
use OxidEsales\EshopCommunity\Internal\Twig\Node\HasRightsNode;
use OxidEsales\EshopCommunity\Internal\Twig\TokenParser\HasRightsTokenParser;

/**
 * Class HasRightsExtensionTest
 */
class HasRightsExtensionTest extends AbstractExtensionTest
{

    /** @var HasRightsExtension */
    protected $extension;

    protected $tags = ['hasrights'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new HasRightsExtension(new HasRightsTokenParser(HasRightsNode::class));
        parent::setUp();
    }

    /**
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\HasRightsExtension::getTokenParsers
     */
    public function testGetTokenParsers(): void
    {
        $tokenParser = $this->extension->getTokenParsers();
        $this->assertInstanceOf(HasRightsTokenParser::class, $tokenParser[0]);
    }
}
