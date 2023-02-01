<?php

if(!defined('_PS_VERSION_')){
    exit;
}

use PrestaShop\Module\DemoDoctrine\Entity\Quote;
use PrestaShop\Module\DemoDoctrine\Entity\QuoteLang;

class webo_SideMapGenerator extends Module
{

    public function __construct()
    {
        $this->name = "webo_sidemapgenerator";
        $this->tab = "others";
        $this->version = "1.0.0";
        $this->author = "Webo.Agency";
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->displayName = $this->trans('Webo sidemape generator', array(), 'Modules.Webo_SideMapGenerator');
        $this->description = $this->trans('Thanks to this module you automatically create cache sidemap filter', array(), 'Modules.Webo_SideMapGenerator');
        parent::__construct();

    }

    public function install() : bool
    {
        if(!$this->checkIfGoogleModuleIsActive())
        {
            $this->_errors[] = $this->trans('To use this module you must enable Google sitemap', array(), 'Modules.Webo_SideMapGenerator');
            return false;
        }
        $tab = new Tab();
        $tab->class_name = 'AdminWeboSideMapGenerator';
        $tab->module = 'webo_sidemapgenerator';
        $tab->icon = 'build';
        $tab->route_name = 'admin_webo_sidemap';
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminAdvancedParameters');
        $tab->active = 1;
        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[(int) $lang['id_lang']] = 'Side Map Generator';
        }
        if(!$tab->save()) {
            return false;
        }
        $this->addVariableToConfigFile();
        $this->createLinkToGsitemapSitemap();
        $this->createMainSitemapFile();
        $this->createFilterSitemapFile($this->createFilterSitemap());
        if(parent::install()) {
            return true;
        }
        return false;
    }

    public function uninstall():bool
    {
        $tab = new Tab((int)Tab::getIdFromClassName('AdminWeboSideMapGenerator'));
        if(Validate::isLoadedObject($tab)) {
            if(!$tab->delete()) {
                return false;
            }
        }
        $this->deleteLinkFromGsitemapSitemap();
        $this->createMainSitemapFile();
        $this->deleteSitemapFilterFile();
        foreach(array(
                    'WEBO_FILTERSITEMAP_TOKEN' => '',
                    'WEBO_FILTERSITEMAP_ACTIVE' => '',
                    'WEBO_FILTERSITEMAP_SITEMAPINDEX' => '',
                ) as $key => $val) {
            if(!Configuration::deleteByName($key, $val)) {
                return false;
            }
        }
        if(parent::uninstall()) {
            return true;
        }
        $this->_errors[] = $this->trans('There was an error during the uninstallation. Please see documentation <a href="https://github.com/webo-agency/webo_taghint">here</a>', array(), 'Modules.Webo_RemoveAllItem.Admin');
        return false;
    }

    /**
     * @return bool
     * this function delete filter site map link from gsitemap sitemap table
     */
    public function deleteLinkFromGsitemapSitemap(): bool
    {
        if(!$this->checkIfFilterIsIndexed()) {
            if (Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'gsitemap_sitemap`')) {
                return Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'gsitemap_sitemap` WHERE `link` = "filter_sitemap.xml"') ? true : false;
            }
        }
        return true;
    }

    /**
     * @return bool
     * this function insert gsitemap link into gsitemap sitemap table
     */
    public function createLinkToGsitemapSitemap():bool
    {
        if(!$this->checkIfFilterIsIndexed()) {
            if (!Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'gsitemap_sitemap` WHERE `link`="filter_sitemap.xml"')) {
                return Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'gsitemap_sitemap` (`link`, `id_shop`) VALUES ("filter_sitemap.xml", ' . (int)$this->context->shop->id . ')') ? true : false;
            }
        }
        return true;
    }


    /**
     * @return array
     * this function is get variable from category lang
     */
    public function returnCategoryLang($category_lang): array
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'category_lang` WHERE `id_category`='.$category_lang.' ORDER BY `id_category` DESC');
    }

    /**
     * @return string
     * this function normalized directory
     */
    protected function normalizeDirectory($directory) : string
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

    /**
     * @return bool
     * this function is action cron
     */
    public function cronAction() : bool
    {
        if($this->checkIfGoogleModuleIsActive()) {
            if (Configuration::get('WEBO_FILTERSITEMAP_ACTIVE') == '1') {
                if ($this->checkIfGoogleModuleIsActive()) {
                    $this->createLinkToGsitemapSitemap();
                    $this->createMainSitemapFile();
                    $this->createFilterSitemapFile($this->createFilterSitemap());
                    return true;
                }
                return false;
            } else {
                $this->deleteLinkFromGsitemapSitemap();
                $this->createMainSitemapFile();
                $this->deleteSitemapFilterFile();
            }
            return false;
        }else {
            Configuration::updateValue('WEBO_FILTERSITEMAP_ACTIVE', '0');
            $this->deleteLinkFromGsitemapSitemap();
            $this->createMainSitemapFile();
            $this->deleteSitemapFilterFile();
            return false;
        }
    }

    /**
     * @return bool
     * this function delete sitemap filter file
     */
    public function deleteSitemapFilterFile()
    {
        if(file_exists($this->normalizeDirectory(_PS_ROOT_DIR_). "filter_sitemap.xml")) {
            return unlink($this->normalizeDirectory(_PS_ROOT_DIR_) . "filter_sitemap.xml") ? true : false;
        }
    }

    /**
     * @return array
     * this function return array all gsitemap sitemap file link
     */
    public function returnGsitemapSitemapLink(): array
    {
        return Db::getInstance()->executeS('SELECT `link` FROM `' . _DB_PREFIX_ . 'gsitemap_sitemap` ORDER BY `link` DESC');
    }

    /**
     * @return bool
     * this function create/update index sitemap file
     */
    public function createMainSitemapFile() : bool
    {
        if(!$this->checkIfFilterIsIndexed()) {
            if ($this->returnGsitemapSitemapLink()) {
                $create = fopen($this->normalizeDirectory(_PS_ROOT_DIR_) . $this->context->shop->id . "_index_sitemap.xml", "wb");
                fwrite($create, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
                foreach ($this->returnGsitemapSitemapLink() as $sitemaps) {
                    fwrite($create, '<sitemap>' . PHP_EOL . '<loc>' . $this->context->link->getBaseLink() . $sitemaps['link'] . '</loc>' . PHP_EOL . '<lastmod>' . date('c') . '</lastmod>' . PHP_EOL . '</sitemap>' . PHP_EOL);
                }
                fwrite($create, '</sitemapindex>');
                fclose($create);
                return true;
            }
        }
        return true;
    }

    /**
     * @return bool
     * this function create filter_sitemap.xml file with all filters
     */
    public function createFilterSitemapFile($array) : bool
    {
            $create = fopen($this->normalizeDirectory(_PS_ROOT_DIR_) . "filter_sitemap.xml", "wb");
            fwrite($create, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL);
            foreach ($array as $arrays) {
                fwrite($create, '<url>' . PHP_EOL . '<loc>' . $arrays . '</loc>' . PHP_EOL . '<lastmod>' . date('c', strtotime(time())) . '</lastmod>' . PHP_EOL . '<changefreq>daily</changefreq>' . PHP_EOL . '<priority>0.5</priority>' . PHP_EOL . '</url>' . PHP_EOL);
            }
            fwrite($create, '</urlset>');
            fclose($create);
            return true;
    }

    /**
     * @return array
     * this function create sitemap
     */
    public function createFilterSitemap() : array
    {
        $array = array();
        foreach($this->returnCategoryProduct() as $category_product)
        {
            foreach ($this->returnCategoryLang($category_product['id_category']) as $category_lang)
            {
                foreach($this->returnLayeredProductAttribute($category_product['id_product']) as $layered_product_attribute)
                {
                    $array[] = $this->returnUrlString($category_lang['id_lang'], $category_lang['id_category'], $category_lang['link_rewrite'], $this->returnAttributeGroupLang($layered_product_attribute['id_attribute_group'], $category_lang['id_lang'])['name'], $this->returnAttributeLang($layered_product_attribute['id_attribute'], $category_lang['id_lang'])['name']);
                }
            }
        }
        return $array;
    }

    /**
     * this function return name attribute group lang
     */
    public function returnAttributeGroupLang($id_attribute_group, $id_lang)
    {
        return Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'attribute_group_lang` WHERE id_attribute_group = '. $id_attribute_group.' AND id_lang = '. $id_lang);
    }

    /**
     * this function return name attribute lang table
     */
    public function returnAttributeLang($id_attribute, $id_lang)
    {
        return Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'attribute_lang` WHERE id_attribute = '. $id_attribute.' AND id_lang = '. $id_lang);
    }

    /**
     * @return array
     * this function return array layered_product_attribute table
     */
    public function returnLayeredProductAttribute($id_product): array
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'layered_product_attribute` WHERE `id_product` = '. $id_product .' ORDER BY id_attribute');
    }

    /**
     * @return string
     * this function return url string
     */
    public function returnUrlString($id_lang, $id_category, $link_rewrite,$filter_group_name, $filter_name): string
    {
        return _PS_BASE_URL_.'/'.Language::getIsoById((int)$id_lang).'/'. $id_category .'-'.$link_rewrite.'?q='. preg_replace('/\s+/', '+',$filter_group_name).'-'.preg_replace('/\s+/', '+',$filter_name);
    }

    /**
     * @return bool
     * this function add config variable to settings
     */
    public function addVariableToConfigFile(): bool
    {
        $variable = "";
        foreach(array(
                    'WEBO_FILTERSITEMAP_TOKEN' => bin2hex(random_bytes(30)),
                    'WEBO_FILTERSITEMAP_ACTIVE' => 1,
                    'WEBO_FILTERSITEMAP_SITEMAPINDEX' => 0,
                ) as $key => $val) {
            if(!Configuration::updateValue($key, $val)) {
                $variable = "1";
            }
        }
        if(!$variable == "1")
        {
            return true;
        }
        return false;
    }

    /**
     * @return array
     * this function return all product category
     */
    public function returnProductCategory($variable) : array
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'category_product` WHERE `id_category`= '. $variable.' ORDER BY `id_category`');
    }


    /**
     * @return array
     * this function array all category product
     */
    public function returnCategoryProduct():array
    {
        return Db::getInstance()->executeS('SELECT * FROM `'. _DB_PREFIX_ .'category_product` ORDER BY `id_category` DESC');
    }

    /**
     * @return bool
     * this functiopn check if google sitemap is active
     */
    public function checkIfGoogleModuleIsActive() : bool
    {
        return $this->checkIfFilterIsIndexed() ? true : Module::isEnabled('gsitemap') ? true : false;
    }

    /**
     * @return bool
     * this function check if
     */
    public function checkIfFilterIsIndexed():bool
    {
        return Configuration::get('WEBO_FILTERSITEMAP_SITEMAPINDEX') == 0 ? true : false;
    }

}