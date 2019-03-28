<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\SmartWordwrapLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\SmartWordwrapExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class SmartWordwrapExtensionTest
 */
class SmartWordwrapExtensionTest extends AbstractExtensionTest
{

    /** @var SmartWordwrapExtension */
    protected $extension;

    protected $filters = ['smart_wordwrap'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new SmartWordwrapExtension(new SmartWordwrapLogic());
    }

    /**
     * @return array
     */
    public function provider(): array
    {
        return [
            [
                [
                    'string' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas risus ipsum, ornare id scelerisque non, porta nec nulla.'
                ],
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas risus ipsum,
ornare id scelerisque non, porta nec nulla.'
            ],
            [
                [
                    'string' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length' => 20
                ],
                'Lorem ipsum dolor
sit amet,
consectetur
adipiscing elit.'
            ],
            [
                [
                    'string' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length' => 20,
                    'break'  => '<br/>'
                ],
                'Lorem ipsum dolor<br/>sit amet,<br/>consectetur<br/>adipiscing elit.'
            ],
            [
                [
                    'string'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length'  => 10,
                    'cutRows' => 7
                ],
                'Lorem
ipsum
dolor sit
amet,
consectetu
r
adipisc...'
            ],
            [
                [
                    'string'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length'    => 20,
                    'tolerance' => -30
                ],
                'Lorem ipsum dolor
sit amet,
consectetur
adipiscing elit.'
            ],
            [
                [
                    'string'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length'    => 20,
                    'tolerance' => 30
                ],
                'Lorem ipsum dolor
sit amet,
consectetur
adipiscing elit.'
            ],
            [
                [
                    'string'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length'    => 20,
                    'tolerance' => 150
                ],
                'Lorem ipsum dolor
sit amet,
consectetur
adipiscing elit.'
            ],
            [
                [
                    'string'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    'length'  => 10,
                    'cutRows' => 7,
                    'etc'     => '[...]'
                ],
                'Lorem
ipsum
dolor sit
amet,
consectetu
r
adipi[...]'
            ]
        ];
    }

    /**
     * @param array  $params
     * @param string $expectedString
     *
     * @covers       \OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\SmartWordwrapExtension::smartWordWrap
     * @dataProvider provider
     */
    public function testSmartWordWrap(array $params, string $expectedString): void
    {
        $string = $params['string'];
        $length = isset($params['length']) ? $params['length'] : 80;
        $break = isset($params['break']) ? $params['break'] : "\n";
        $cutRows = isset($params['cutRows']) ? $params['cutRows'] : 0;
        $tolerance = isset($params['tolerance']) ? $params['tolerance'] : 0;
        $etc = isset($params['etc']) ? $params['etc'] : '...';

        $actualString = $this->extension->smartWordwrap($string, $length, $break, $cutRows, $tolerance, $etc);
        $this->assertEquals($expectedString, $actualString);
    }
}
