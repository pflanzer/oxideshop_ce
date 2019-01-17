<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic;

use Twig\Environment;

/**
 * Class InsertNewBasketItemLogic
 *
 * @package OxidEsales\EshopCommunity\Internal\Adapter\TemplateLogic
 * @author  Jędrzej Skoczek
 */
class InsertNewBasketItemLogic
{

    /**
     * @param array             $params
     * @param \Twig_Environment $templateEngine
     *
     * @return string
     */
    public function getNewBasketItemTemplate(array $params, $templateEngine): string
    {
        $renderedTemplate = '';
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();

        $types = ['0' => 'none', '1' => 'message', '2' => 'popup', '3' => 'basket'];
        $newBasketItemMessage = $config->getConfigParam('iNewBasketItemMessage');

        // If correct type of message is expected
        if ($newBasketItemMessage && $params['type'] && ($params['type'] != $types[$newBasketItemMessage])) {
            $correctMessageType = false;
        } else {
            $correctMessageType = true;
        }

        //name of template file where is stored message text
        $templateName = $params['tpl'] ? $params['tpl'] : 'inc_newbasketitem.snippet.tpl';

        //always render for ajaxstyle popup
        $render = $params['ajax'] && ($newBasketItemMessage == 2);

        //fetching article data
        $newItem = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('_newitem');

        if ($newItem && $correctMessageType) {
            // loading article object here because on some system passing article by session causes problems
            $newItem->oArticle = oxNew('oxarticle');

            $newItem->oArticle->Load($newItem->sId);

            // passing variable to template with unique name
            if ($templateEngine instanceof \Smarty) {
                $templateEngine->assign('_newitem', $newItem);
            } elseif ($templateEngine instanceof Environment) {
                $templateEngine->addGlobal('_newitem', $newItem);
            }

            // deleting article object data
            \OxidEsales\Eshop\Core\Registry::getSession()->deleteVariable('_newitem');

            $render = true;
        }

        // returning generated message content
        if ($render && $correctMessageType) {
            if ($templateEngine instanceof \Smarty) {
                $renderedTemplate = $templateEngine->fetch($templateName);
            } elseif ($templateEngine instanceof Environment) {
                $template = $templateEngine->load($templateName);
                $renderedTemplate = $template->render();
            }
        }

        return $renderedTemplate;
    }
}
