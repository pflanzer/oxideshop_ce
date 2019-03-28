<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\MathExtension;

/**
 * Class MathExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class MathExtensionTest extends AbstractExtensionTest
{

    /** @var MathExtension */
    protected $extension;

    protected $functions = ['cos', 'sin', 'tan', 'exp', 'log', 'log10', 'pi', 'sqrt'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new MathExtension();
    }
}
