<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions;

use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\InsertNewBasketItemLogicTwig;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\InsertNewBasketItemExtension;
use Twig\Environment;

/**
 * Class InsertNewBasketItemExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class InsertNewBasketItemExtensionTest extends AbstractExtensionTest
{

    /** @var InsertNewBasketItemExtension */
    protected $extension;

    protected $functions = ['insert_new_basket_item'];

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->extension = new InsertNewBasketItemExtension(new InsertNewBasketItemLogicTwig());
    }

    public function testInsertNewBasketItem(): void
    {
        $environment = $this->getEnvironment();

        /** @var Environment $environment */

        $params = [
            'tpl'  => 'template.html.twig',
            'type' => 'message',
            'ajax' => false
        ];

        $renderedTemplate = $this->extension->insertNewBasketItem($environment, $params);

        $this->assertEquals('', $renderedTemplate);
    }
}
