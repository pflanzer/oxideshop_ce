<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\InsertTrackerExtension;
use Twig_Environment;

/**
 * Class InsertTrackerExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class InsertTrackerExtensionTest extends AbstractExtensionTest
{

    /** @var InsertTrackerExtension */
    protected $extension;

    protected $functions = ['insert_tracker'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new InsertTrackerExtension();
    }

    public function testInsertTracker(): void
    {
        /** @var Twig_Environment $environment */
        $environment = $this->getEnvironment();

        $this->assertEquals('', $this->extension->insertTracker($environment, []));
    }
}
