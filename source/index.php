<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

require_once dirname(__FILE__) . "/bootstrap.php";

/**
 * Redirect to Setup, if shop is not configured
 */
redirectIfShopNotConfigured();

$themeSwitcher = new \OxidEsales\TestingLibrary\Services\ThemeSwitcher\ThemeSwitcher([]);
$themeSwitcher->activateTwigTheme();

//Starts the shop
OxidEsales\EshopCommunity\Core\Oxid::run();
