<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace  OxidEsales\EshopCommunity\Application\Component\Widget;

use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Templating\TraditionalEngineInterface;

/**
 * Category tree widget.
 * Forms category tree.
 */
class CategoryTree extends \OxidEsales\Eshop\Application\Component\Widget\WidgetController
{
    /**
     * Names of components (classes) that are initiated and executed
     * before any other regular operation.
     * Cartegory component used in template.
     *
     * @var array
     */
    protected $_aComponentNames = ['oxcmp_categories' => 1];

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'widget/sidebar/categorytree.tpl';

    /**
     * Executes parent::render(), assigns template name and returns it
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $templating = $this->getTemplating();

        if ($sTpl = $this->getViewParameter("sWidgetType")) {
            $sTemplateName = 'widget/' . basename($sTpl) . '/categorylist.tpl';
            if ($templating->exists($sTemplateName)) {
                $this->_sThisTemplate = $sTemplateName;
            }
        }

        return $this->_sThisTemplate;
    }

    /**
     * @internal
     *
     * @return TraditionalEngineInterface
     */
    protected function getTemplating()
    {
        return $this->getContainer()->get(TraditionalEngineInterface::class);
    }

    /**
     * Returns the deep level of category tree
     *
     * @return null
     */
    public function getDeepLevel()
    {
        return $this->getViewParameter("deepLevel");
    }

    /**
     * Content category getter.
     *
     * @return bool|string
     */
    public function getContentCategory()
    {
        $request = Registry::get(Request::class);
        return $request->getRequestParameter('oxcid', false);
    }
}
