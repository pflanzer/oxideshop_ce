<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class GetRequestVariablesExtension
 *
 * @package OxidEsales\EshopCommunity\Internal\Twig\Extensions
 * @author  Jędrzej Skoczek
 */
class GetRequestVariablesExtension extends AbstractExtension
{

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_COOKIE', [$this, 'getCookie']),
            new TwigFunction('get_GET', [$this, 'getGet'])
        ];
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getCookie(string $key)
    {
        $cookie = NULL;
        if(isset($_COOKIE[$key])){
            $cookie = $_COOKIE[$key];
        }
        return $cookie;
    }

    public function getGet(string $key)
    {
        $get = NULL;
        if(isset($_GET[$key])){
            $get = $_GET[$key];
        }
        return $get;
    }
}
