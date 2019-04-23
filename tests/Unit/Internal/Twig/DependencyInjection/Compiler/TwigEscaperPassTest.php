<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\DependencyInjection\Compiler;

use OxidEsales\EshopCommunity\Internal\Twig\DependencyInjection\Compiler\TwigEscaperPass;
use OxidEsales\EshopCommunity\Internal\Twig\TwigEngine;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TwigEscaperPassTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class TwigEscaperPassTest extends TestCase
{

    public function testEscaperPass(): void
    {
        $builder = new ContainerBuilder();
        $builder->register(TwigEngine::class);
        $pass = new TwigEscaperPass();

        $builder->register('test_escaper_1')
            ->addTag('twig.escaper');

        $builder->register('test_escaper_2')
            ->addTag('twig.escaper');

        $pass->process($builder);

        $this->assertArrayHasKey('test_escaper_1', $builder->findTaggedServiceIds('twig.escaper'));
        $this->assertArrayHasKey('test_escaper_2', $builder->findTaggedServiceIds('twig.escaper'));
    }
}