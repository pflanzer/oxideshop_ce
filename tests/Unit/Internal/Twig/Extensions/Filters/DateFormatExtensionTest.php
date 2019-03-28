<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

//define('NEW_DIRECTORY_SEPARATOR', "//");
//
//use const NEW_DIRECTORY_SEPARATOR as DIRECTORY_SEPARATOR;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\DateFormatHelper;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\DateFormatExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class DateFormatExtensionTest
 *
 * Test overrides DIRECTORY_SEPARATOR const
 *
 * @package OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters
 */
class DateFormatExtensionTest extends AbstractExtensionTest
{

    /**
     * @var DateFormatExtension
     */
    protected $extension;

    protected $filters = ['date_format'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $dateFormatHelper = new DateFormatHelper();
        $this->extension = new DateFormatExtension($dateFormatHelper);
    }

    /**
     * @return array
     */
    public function provider(): array
    {
        return [

            //dummy data
            ['', '', '', null],
            ['foo', '', '', null],
            ['', 'foo', '', null],
            ['', '', 'foo', false],
            ['foo', 'foo', '', 'foo'],
            ['foo', 'foo', 'foo', 'foo'],

            //provided input string
            [20181201101525, '%Y-%m-%d %H:%M:%S', '', '2018-12-01 10:15:25'],           //mysql format
            [1543850519, '%Y-%m-%d %H:%M:%S', '', '2018-12-03 16:21:59'],               //time()
            ['Dec 03 15:21:59 2018', '%Y-%m-%d %H:%M:%S', '', '2018-12-03 15:21:59'],   //string time

            //no input string provided, default date used
            ['', '%Y-%m-%d %H:%M:%S', 20181201101525, '2018-12-01 10:15:25'],           //mysql format
            ['', '%Y-%m-%d %H:%M:%S', 1543850519, '2018-12-03 16:21:59'],               //time()
            ['', '%Y-%m-%d %H:%M:%S', 'Dec 03 15:21:59 2018', '2018-12-03 15:21:59'],   //string time
        ];
    }

    /**
     * @param mixed  $string
     * @param string $format
     * @param string $default_date
     * @param mixed $expectedDate
     *
     * @dataProvider provider
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\DateFormatExtension::dateFormat
     */
    public function testDateFormat(string $string, string $format, string $default_date, $expectedDate): void
    {
        $actualDate = $this->extension->dateFormat($string, $format, $default_date);
        $this->assertEquals($expectedDate, $actualDate);
    }
}
