<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Application\Model\Content;
use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\IfContentLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\IfContentExtension;

/**
 * Class IfContentExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class IfContentExtensionTest extends AbstractExtensionTest
{

    /** @var IfContentExtension */
    protected $extension;

    protected $tags = ['ifcontent'];

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->extension = new IfContentExtension(new IfContentLogic());
    }

    public function testGetContent(): void
    {
        $content = $this->extension->getContent('oxagb');
        $this->assertInstanceOf(Content::class, $content);

        $content = $this->extension->getContent('oxagb');
        $this->assertInstanceOf(Content::class, $content);
    }

    public function testGetContentNoIdentContent(): void
    {
        $content = $this->extension->getContent('not_existing');
        $this->assertFalse($content);
    }

    public function testGetContentNoOxidContent(): void
    {
        $content = $this->extension->getContent(null, 'not_existing');
        $this->assertFalse($content);
    }
}
