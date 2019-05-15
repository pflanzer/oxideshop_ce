<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Codeception;

use OxidEsales\EshopCommunity\Tests\Codeception\Step\Acceptance\ProductNavigation;
use OxidEsales\EshopCommunity\Tests\Codeception\Step\Acceptance\Start;
use OxidEsales\Codeception\Module\Translation\Translator;

class ProductCompareCest
{
    /**
     * @group myAccount
     *
     * @param AcceptanceTester  $I
     * @param ProductNavigation $productNavigation
     */
    public function enableProductCompare(AcceptanceTester $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('if product compare functionality is enabled');

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        $userData = $this->getExistingUserData();

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $detailsPage->loginUser($userData['userLoginName'], $userData['userPassword'])
            ->checkCompareListItemCount(0)
            ->addToCompareList()
            ->checkCompareListItemCount(1);

        $userAccountPage = $detailsPage->openAccountPage();
        $I->see(Translator::translate('MY_PRODUCT_COMPARISON'));
        $I->see(Translator::translate('PRODUCT').' 1');

        $userAccountPage = $userAccountPage->logoutUser()
            ->login($userData['userLoginName'], $userData['userPassword']);
        $I->see(Translator::translate('MY_PRODUCT_COMPARISON'));
        $I->see(Translator::translate('PRODUCT').' 1');

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $detailsPage->removeFromCompareList()
            ->checkCompareListItemCount(0);
    }

    /**
     * @group myAccount
     *
     * @param Start              $I
     * @param ProductNavigation  $productNavigation
     */
    public function addProductToUserCompareList(Start $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('user product compare list functionality');

        $productData1 = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        $productData2 = [
            'id' => 1001,
            'title' => 'Test product 1 [EN] šÄßüл',
            'desc' => 'Test product 1 short desc [EN] šÄßüл',
            'price' => '100,00 € *'
        ];

        $productData3 = [
            'id' => 10014,
            'title' => '14 EN product šÄßüл',
            'desc' => '13 EN description šÄßüл',
            'price' => 'from 15,00 € *'
        ];

        $userData = $this->getExistingUserData();

        $I->loginOnStartPage($userData['userLoginName'], $userData['userPassword']);

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData1['id']);
        $I->see($productData1['title']);
        //add to compare list
        $detailsPage->addToCompareList()
            ->checkCompareListItemCount(1);

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData2['id']);
        $I->see($productData2['title']);
        //add to compare list
        $detailsPage->addToCompareList()
            ->checkCompareListItemCount(2);

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData3['id']);
        $I->see($productData3['title']);
        //add to compare list
        $detailsPage->addToCompareList()
            ->checkCompareListItemCount(3);

        //open compare list page
        $comparePage = $detailsPage->openProductComparePage();

        // TODO: refactor
        $I->seeElement("#compareRight_1000");
        $I->seeElement("#compareRight_1001");
        $I->seeElement("#compareLeft_10014");
        $comparePage->seeProductData($productData1, 1);
        $comparePage->seeProductData($productData2, 2);
        $comparePage->seeProductData($productData3, 3);

        // TODO: add variant assertion
        //$this->assertEquals("selvar1 [EN] šÄßüл +1,00 € selvar2 [EN] šÄßüл selvar3 [EN] šÄßüл -2,00 € selvar4 [EN] šÄßüл +2%", $I->clearString($this->getText("//div[@id='compareSelections_2']//ul")));
        //$this->assertEquals("var1 [EN] šÄßüл var2 [EN] šÄßüл", $I->clearString($this->getText("//div[@id='compareVariantSelections_3']//ul")));

        //open product details page
        $detailsPage = $comparePage->openProductDetailsPage(1);
        $I->see($productData1['title'], $detailsPage::$productTitle);
        $comparePage = $detailsPage->openProductComparePage();
        $detailsPage = $comparePage->openProductDetailsPage(2);
        $I->see($productData2['title'], $detailsPage::$productTitle);
        $comparePage = $detailsPage->openProductComparePage();

        $comparePage->seeProductAttributeName('Test attribute 1 [EN] šÄßüл:',1);
        $comparePage->seeProductAttributeValue('attr value 1 [EN] šÄßüл', 1, 1);
        $comparePage->seeProductAttributeValue('attr value 11 [EN] šÄßüл', 1, 2);
        $comparePage->seeProductAttributeName('Test attribute 3 [EN] šÄßüл:',2);
        $comparePage->seeProductAttributeValue('attr value 3 [EN] šÄßüл', 2, 1);
        $comparePage->seeProductAttributeValue('attr value 3 [EN] šÄßüл', 2, 2);
        $comparePage->seeProductAttributeName('Test attribute 2 [EN] šÄßüл:',3);
        $comparePage->seeProductAttributeValue('attr value 2 [EN] šÄßüл', 3, 1);
        $comparePage->seeProductAttributeValue('attr value 12 [EN] šÄßüл', 3, 2);

        $comparePage->moveItemToRight($productData1['id']);
        $comparePage->seeProductData($productData1, 2);
        $comparePage->seeProductData($productData2, 1);

        $comparePage->moveItemToLeft($productData1['id']);
        $comparePage->seeProductData($productData1, 1);
        $comparePage->seeProductData($productData2, 2);

        $comparePage->removeProductFromList($productData1['id']);
        $comparePage->removeProductFromList($productData2['id']);
        //TODO: is not working with flow theme anymore
        //$I->see(Translator::translate('MESSAGE_SELECT_MORE_PRODUCTS'));
        $comparePage->removeProductFromList($productData3['id']);
        $I->see(Translator::translate('MESSAGE_SELECT_AT_LEAST_ONE_PRODUCT'));

    }

    /**
     * @group myAccount
     *
     * @param Start             $I
     * @param ProductNavigation $productNavigation
     */
    public function disableProductCompare(Start $I, ProductNavigation $productNavigation)
    {
        $I->wantToTest('if product compare functionality is correctly disabled');

        //(Use product compare) is disabled
        $I->updateConfigInDatabase('bl_showCompareList', false);

        $productData = [
            'id' => 1000,
            'title' => 'Test product 0 [EN] šÄßüл',
            'desc' => 'Test product 0 short desc [EN] šÄßüл',
            'price' => '50,00 € *'
        ];

        $userData = $this->getExistingUserData();

        $I->loginOnStartPage($userData['userLoginName'], $userData['userPassword']);

        //open details page
        $detailsPage = $productNavigation->openProductDetailsPage($productData['id']);
        $I->see($productData['title']);

        $I->dontSeeElement($detailsPage::$addToCompareListLink);
        $detailsPage->openAccountMenu();
        $I->dontSee(Translator::translate('MY_PRODUCT_COMPARISON'));
        $detailsPage->closeAccountMenu();

        $accountPage = $detailsPage->openAccountPage();
        $I->dontSee(Translator::translate('MY_PRODUCT_COMPARISON'), $accountPage::$dashboardCompareListPanelHeader);

        $I->cleanUp();
        //(Use product compare) is enabled
        $I->updateConfigInDatabase('bl_showCompareList', true);
    }

    public function _failed(AcceptanceTester $I)
    {
        $I->cleanUp();
        $I->clearShopCache();
    }

    private function getExistingUserData()
    {
        return \Codeception\Util\Fixtures::get('existingUser');
    }

}
