<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Smarty;


use OxidEsales\EshopCommunity\Internal\Templating\TemplateRenderer;

class SmartyTemplateRendererBridge
{
    private $engine;

    public function getTemplateRenderer()
    {
        return new TemplateRenderer($this->getTemplateEngine());
    }

    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    public function getTemplateEngine()
    {
        if ($this->engine) {
            return new SmartyEngine($this->engine);
        } else {
            return new SmartyFactory();
        }
    }

    public function getEngine()
    {
        $this->engine;
    }
}