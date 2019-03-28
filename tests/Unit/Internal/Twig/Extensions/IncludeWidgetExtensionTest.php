<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\WidgetControl;
use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\IncludeWidgetLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\IncludeWidgetExtension;

/**
 * Class IncludeWidgetExtensionTest
 */
class IncludeWidgetExtensionTest extends AbstractExtensionTest
{

    /** @var IncludeWidgetExtension */
    protected $extension;

    protected $functions = ['include_widget'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $includeWidgetLogic = new IncludeWidgetLogic();
        $this->extension = new IncludeWidgetExtension($includeWidgetLogic);
    }

    /**
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\IncludeWidgetExtension::includeWidget
     */
    public function testIncludeWidget(): void
    {
        $widgetControl = $this->createMock(WidgetControl::class);
        $widgetControl->expects($this->any())->method("start")->will($this->returnValue('html'));
        Registry::set(WidgetControl::class, $widgetControl);

        $actual = $this->extension->includeWidget(['cl' => 'oxwTagCloud', 'blShowTags' => 1]);
        $this->assertEquals('html', $actual);
    }

    /**
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\IncludeWidgetExtension::includeWidget
     */
    public function testIncludeWidgetWithParentViews(): void
    {
        $widgetControl = $this->createMock(WidgetControl::class);
        $widgetControl->method("start")->will($this->returnArgument(3));
        Registry::set(WidgetControl::class, $widgetControl);

        $actual = $this->extension->includeWidget(['cl' => 'oxwTagCloud', 'blShowTags' => 1, '_parent' => 'parent|views']);
        $this->assertEquals(['parent', 'views'], $actual);
    }
}
