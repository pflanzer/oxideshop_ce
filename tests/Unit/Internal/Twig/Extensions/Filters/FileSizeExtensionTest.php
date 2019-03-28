<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extension\Filters;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\FileSizeLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FileSizeExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class FileSizeExtensionTest
 */
class FileSizeExtensionTest extends AbstractExtensionTest
{

    /** @var FileSizeExtension */
    protected $extension;

    protected $filters = ['file_size'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new FileSizeExtension(new FileSizeLogic());
    }

    /**
     * @return array
     */
    public function provider(): array
    {
        return [
            [1023, '1023 B'],
            [1025, '1.0 KB'],
            [1024 * 1024 * 1.1, '1.1 MB'],
            [1024 * 1024 * 1024 * 1.3, '1.3 GB']
        ];
    }

    /**
     * @param int $fileSize
     * @param string $expectedFileSize
     *
     * @dataProvider provider
     * @covers \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\FileSizeExtension::fileSize
     */
    public function testFileSize(int $fileSize, string $expectedFileSize): void
    {
        $actualFileSize = $this->extension->fileSize($fileSize);
        $this->assertEquals($expectedFileSize, $actualFileSize);
    }
}
