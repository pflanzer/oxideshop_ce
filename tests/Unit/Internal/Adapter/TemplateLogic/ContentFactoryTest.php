<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Adapter\TemplateLogic;

use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\ContentFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ContentFactoryTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class ContentFactoryTest extends TestCase
{

    /** @var ContentFactory */
    private $contentFactory;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->contentFactory = new ContentFactory();
        parent::setUp();
    }

    public function testGetContentIdent(): void
    {
        $content = $this->contentFactory->getContent('ident', 'oxemailfooter');
        $this->assertInstanceOf(Content::class, $content);
    }

    public function testGetContentOxid(): void
    {
        $content = $this->contentFactory->getContent('oxid', '3194668fde854d711.73798992');
        $this->assertInstanceOf(Content::class, $content);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Cannot load content. Not provided neither ident nor oxid.
     */
    public function testGetContentInvalidKey(): void
    {
        $this->contentFactory->getContent('invalid_key', '1234');
    }
}
