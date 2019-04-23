<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\Filters;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic\TranslateFilterLogic;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\Filters\TranslateExtension;
use OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extensions\AbstractExtensionTest;

/**
 * Class TranslateExtensionTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class TranslateExtensionTest extends AbstractExtensionTest
{

    /** @var TranslateExtension */
    protected $extension;

    protected $filters = ['translate'];

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        $this->extension = new TranslateExtension(new TranslateFilterLogic());
    }

    /**
     * Provides data to testSimpleTranslating
     *
     * @return array
     */
    public function simpleTranslatingProvider(): array
    {
        return [
            ["{{ 'FIRST_NAME'|translate }}", 0, 'Vorname'],
            ["{{ 'FIRST_NAME'|translate }}", 1, 'First name'],
            ["{{ 'VAT'|translate }}", 1, 'VAT']
        ];
    }

    /**
     * Tests simple translating, where only translation is fetched
     *
     * @param string $template
     * @param int    $languageId
     * @param string $expected
     *
     * @dataProvider simpleTranslatingProvider
     */
    public function testSimpleTranslating(string $template, int $languageId, string $expected): void
    {
        $this->setLanguage($languageId);
        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }

    /**
     * Provides data to testTranslatingWithArguments
     *
     * @return array
     */
    public function withArgumentsProvider(): array
    {
        return [
            ["{{ 'MANUFACTURER_S'|translate('Opel') }}", 0, '| Hersteller: Opel'],
            ["{{ 'MANUFACTURER_S'|translate('Opel') }}", 1, 'Manufacturer: Opel'],
            ["{{ 'INVITE_TO_SHOP'|translate(['Admin', 'OXID Shop']) }}", 0, 'Eine Einladung von Admin OXID Shop zu besuchen.'],
            ["{{ 'INVITE_TO_SHOP'|translate(['Admin', 'OXID Shop']) }}", 1, 'An invitation from Admin to visit OXID Shop']
        ];
    }

    /**
     * Tests value translating when translating strings containing %s
     *
     * @param string $template
     * @param int    $languageId
     * @param string $expected
     *
     * @dataProvider withArgumentsProvider
     */
    public function testTranslatingWithArguments(string $template, int $languageId, string $expected): void
    {
        $this->setLanguage($languageId);
        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }

    /**
     * Provides data to testTranslateFrontend_isMissingTranslation
     *
     * @return array
     */
    public function missingTranslationProviderFrontend(): array
    {
        return [
            [true, "{{ 'MY_MISING_TRANSLATION'|translate }}", 'MY_MISING_TRANSLATION'],
            [false, "{{ 'MY_MISING_TRANSLATION'|translate }}", 'ERROR: Translation for MY_MISING_TRANSLATION not found!'],
        ];
    }

    /**
     * @param bool   $isProductiveMode
     * @param string $template
     * @param string $expected
     *
     * @dataProvider missingTranslationProviderFrontend
     */
    public function testTranslateFrontend_isMissingTranslation(bool $isProductiveMode, string $template, string $expected): void
    {
        $this->setAdminMode(false);
        $this->setLanguage(1);

        $oShop = $this->getConfig()->getActiveShop();
        $oShop->oxshops__oxproductive = new Field($isProductiveMode);
        $oShop->save();

        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }

    /**
     * Provides data to testTranslateAdmin_isMissingTranslation
     *
     * @return array
     */
    public function missingTranslationProviderAdmin(): array
    {
        return [
            ["{{ 'MY_MISING_TRANSLATION'|translate }}", 'ERROR: Translation for MY_MISING_TRANSLATION not found!'],
        ];
    }

    /**
     * @param string $template
     * @param string $expected
     *
     * @dataProvider missingTranslationProviderAdmin
     */
    public function testTranslateAdmin_isMissingTranslation(string $template, string $expected): void
    {
        $this->setLanguage(1);
        $this->setAdminMode(true);

        $this->assertEquals($expected, $this->getTemplate($template)->render([]));
    }
}