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
            new TwigFunction('get_php_cookie', [$this, 'getPhpCookie']),
            new TwigFunction('get_php_get', [$this, 'getPhpGet'])
        ];
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getPhpCookie(string $key)
    {
        $cookie = NULL;
        if(isset($_COOKIE[$key])){
            $cookie = $_COOKIE[$key];
        }
        return $cookie;
    }

    public function getPhpGet(string $key)
    {
        $get = NULL;
        if(isset($_GET[$key])){
            $get = $_GET[$key];
        }
        return $get;
    }
}
