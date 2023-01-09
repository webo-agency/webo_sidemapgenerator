<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use Symfony\Component\HttpFoundation\Response;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminaWeboSideMapGeneratorController extends ModuleAdminController
{
    /** $sitemap_link string */
    public $sitemap_link = "filter_sitemap.xml";

    /** $gsitemap_table string */
    public $gsitemap_table = _DB_PREFIX_ ."gsitemap_sitemap";

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
        $this->id_lang = $this->context->language->id;
        $this->default_from_language = $this->context->language->id;
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Abc', array(), "Modules.WeboSideMapGenerator.Admin"),
                'icon' => 'icon-list-ul'
            ],
            'input' => [
                ['name'=>'name','type'=>'input','label'=>'Select Tag:','required'=>true],
            ],
        ];
        $this->type_array = array(
            'home',
            'meta',
            'product',
            'category',
            'cms',
            'module',
        );
        return $this->abc();
    }

    /**
     * @return webo_sidemapgenerator
     */
    private function getModule()
    {
        /* @phpstan-ignore-next-line */
        return $this->module;
    }

    public function postProcess()
    {
        if(Tools::isSubmit('webo_generate_token'))
        {
            if($this->getModuleInformation()->addVariableToConfigFile()) {
                $this->confirmations[] = "You generate new token";
            } else {
                $this->errors[] = "Somethings wrong, we can't create new token";
            }
        }
        return parent::postProcess();
    }

//    public function renderForm()
//    {
//        /** @var Tag $obj */
//        if (!($obj = $this->loadObject(true))) {
//            return;
//        }
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

    public function initContent()
    {
        parent::initContent();
        $this->render('@Modules/webo_sidemapgenerator/templates/admin/DisplayAdminSettings.html.twig');
//        $this->setTemplate('template.tpl');
    }
//    public function initContent() {
//
//        parent::initContent();
//        $smarty = $this->context->smarty;
//        $content = $smarty->fetch(_PS_MODULE_DIR_ . 'webo_sidemapgenerator/views/templates/admin/template.tpl');
//        $this->context->smarty->assign(array('content' => $this->content . $content));
//
//    }

    public function abc()
    {
        if (@fopen($this->normalizeDirectory(_PS_ROOT_DIR_) . '/test.txt', 'wb') == false) {
            $this->context->smarty->assign('google_maps_error', $this->trans('An error occured while trying to check your file permissions. Please adjust your permissions to allow PrestaShop to write a file in your root directory.', array(), 'Modules.Gsitemap.Admin'));

            return false;
        } else {
            @unlink($this->normalizeDirectory(_PS_ROOT_DIR_) . 'test.txt');
        }

        $type = Tools::getValue('type') ? Tools::getValue('type') : '';
        $languages = Language::getLanguages(true, $this->context->shop->id);
        $lang_stop = Tools::getValue('lang') ? true : false;
        $id_obj = Tools::getValue('id') ? (int) Tools::getValue('id') : 0;
        foreach ($languages as $lang) {
            $i = 0;
            $index = (Tools::getValue('index') && Tools::getValue('lang') == $lang['iso_code']) ? (int) Tools::getValue('index') : 0;
            if ($lang_stop && $lang['iso_code'] != Tools::getValue('lang')) {
                continue;
            } elseif ($lang_stop && $lang['iso_code'] == Tools::getValue('lang')) {
                $lang_stop = false;
            }

            $link_sitemap = array();
            foreach ($this->type_array as $type_val) {
                if ($type == '' || $type == $type_val) {
                    $function = 'get' . Tools::ucfirst($type_val) . 'Link';
//                    if (!$this->$function($link_sitemap, $lang, $index, $i, $id_obj)) {
//                        return false;
//                    }
                    $type = '';
                    $id_obj = 0;
                }
            }
//            $this->recursiveSitemapCreator($link_sitemap, $lang['iso_code'], $index);
//            return $link_sitemap;
            $page = '';
            $index = 0;
            return "haha";
        }
    }

    protected function normalizeDirectory($directory)
    {
        $last = $directory[Tools::strlen($directory) - 1];

        if (in_array($last, array(
            '/',
            '\\',
        ))) {
            $directory[Tools::strlen($directory) - 1] = DIRECTORY_SEPARATOR;

            return $directory;
        }

        $directory .= DIRECTORY_SEPARATOR;

        return $directory;
    }

    public function getModuleInformation()
    {
        return Module::getInstanceByName('webo_sidemapgenerator');
    }

}