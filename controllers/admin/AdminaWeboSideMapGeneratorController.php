<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminaWeboSideMapGeneratorController extends FrameworkBundleAdminController
{
    /** $sitemap_link string */
    public $sitemap_link = "filter_sitemap.xml";

    /** $gsitemap_table string */
    public $gsitemap_table = _DB_PREFIX_ ."gsitemap_sitemap";

//    public function indexAction() :Response
//    {
//        return $this->render('@Modules/webo_sidemapgenerator/templates/admin/DisplayAdminSettings.html.twig');
//    }

//    public function __construct()
//    {
//        parent::__construct();
//        $this->bootstrap = true;
//        $this->id_lang = $this->context->language->id;
//        $this->default_from_language = $this->context->language->id;
//        $this->fields_form = [
//            'legend' => [
//                'title' => $this->trans('Abc', array(), "Modules.WeboSideMapGenerator.Admin"),
//                'icon' => 'icon-list-ul'
//            ],
//            'input' => [
//                ['name'=>'name','type'=>'input','label'=>'Select Tag:','required'=>true],
//            ],
//        ];
//        $this->addSiteMapLink();
//    }

//    public function processForm(Request $request, )

//    public function renderForm()
//    {
//        $this->fields_form = [
//            'legend' => [
//                'title' => $this->trans('Tag', [], 'Admin.Shopparameters.Feature'),
//                'icon' => 'icon-list-ul'
//            ],
//            'input' => [
//                ['name'=>'name','type'=>'select','label'=>'Select Tag:','required'=>true, 'options'=>['query'=> $tag, 'id'=> 'id_tag', 'name'=> 'name']],
//            ],
//            'submit' => [
//                'title' => $this->trans('Save', [], 'Admin.Actions'),
//            ]
//        ];
//        return parent::renderForm();
//    }

//    public function addSiteMapLink()
//    {
//        if(!Db::getInstance()->executeS('SELECT * FROM `'. $this->gsitemap_table.'` WHERE `link` = "'. $this->sitemap_link .'" ORDER BY  `link` DESC')) {
//            return Db::getInstance()->execute('INSERT INTO `' . $this->gsitemap_table. '` (`link`, id_shop) VALUES (\'' . $this->sitemap_link . '\', ' . (int)$this->context->shop->id . ')');
//        }
//        return false;
//    }
//
//    public function deleteSiteMapLink() : string
//    {
//        if(Db::getInstance()->executeS('SELECT * FROM `'. $this->gsitemap_table.'` WHERE `link` = "'. $this->sitemap_link .'" ORDER BY  `link` ASC')) {
//                Db::getInstance()->execute('DELETE FROM `'. $this->gsitemap_table.'` WHERE link = "'. $this->sitemap_link .'"');
//        }
//        return false;
//    }

}