<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\FormatTimeLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FormatTimeExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class FormatTimeExtensionTest
 */
class FormatTimeExtensionTest extends AbstractExtensionTest
{

    /** @var FormatTimeExtension */
    protected $extension;

    protected $filters = ['format_time'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new FormatTimeExtension(new FormatTimeLogic());
    }

    /**
     * @return array
     */
    public function provider(): array
    {
        return [
            [0, '00:00:00'],
            [77834, '21:37:14'],
            [460800, '128:00:00']
        ];
    }

    /**
     * @param int    $seconds
     * @param string $expectedTime
     *
     * @dataProvider provider
     */
    public function testFormatTime(int $seconds, string $expectedTime): void
    {
        $formattedTime = $this->extension->formatTime($seconds);
        $this->assertEquals($expectedTime, $formattedTime);
    }
}
