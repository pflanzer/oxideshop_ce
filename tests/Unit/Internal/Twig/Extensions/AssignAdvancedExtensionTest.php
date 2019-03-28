<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\AssignAdvancedLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\AssignAdvancedExtension;

/**
 * Class AssignAdvancedExtensionTest
 */
class AssignAdvancedExtensionTest extends AbstractExtensionTest
{

    /** @var AssignAdvancedExtension */
    protected $extension;

    protected $functions = ['assign_advanced'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $assignAdvancedLogic = new AssignAdvancedLogic();
        $this->extension = new AssignAdvancedExtension($assignAdvancedLogic);
    }

    public function testAssignAdvanced(): void
    {
        $a = $this->extension->assignAdvanced('foo');
        $this->assertEquals('foo', $a);
    }
}
