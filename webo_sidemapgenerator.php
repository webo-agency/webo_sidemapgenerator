<?php

if(!defined('_PS_VERSION_')){
    exit;
}

class webo_SideMapGenerator extends Module
{

    /** $sitemap_link string */
    public $sitemap_link = "filter_sitemap.xml";

    /** $gsitemap_table string */
    public $gsitemap_table = _DB_PREFIX_ ."gsitemap_sitemap";

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
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminAdvancedParameters');
        $tab->active = 1;
        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[(int) $lang['id_lang']] = 'Side Map Generator';
        }
        if(!$tab->save()) {
            return false;
        }
        $this->addVariableToConfigFile();
        if(parent::install()) {
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        $tab = new Tab((int)Tab::getIdFromClassName('AdminWeboSideMapGenerator'));
        if(Validate::isLoadedObject($tab)) {
            if(!$tab->delete()) {
                return false;
            }
        }
        foreach(array(
                    'WEBO_FILTERSITEMAP_TOKEN' => '',
                    'WEBO_FILTERSITEMAP_ACTIVE' => ''
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


    public function checkIfGoogleModuleIsActive() : bool
    {
        if(Module::isEnabled('gsitemap'))
        {
            return true;
        }
        return false;
    }

    public function addSiteMapLink()
    {
        if(!Db::getInstance()->executeS('SELECT * FROM `'. $this->gsitemap_table.'` WHERE `link` = "'. $this->sitemap_link .'" ORDER BY  `link` DESC')) {
            return Db::getInstance()->execute('INSERT INTO `' . $this->gsitemap_table. '` (`link`, id_shop) VALUES (\'' . $this->sitemap_link . '\', ' . (int)$this->context->shop->id . ')') ? true : false;
        }else {
            return true;
        }
        return false;
    }

    public function deleteSiteMapLink()
    {
        if(Db::getInstance()->executeS('SELECT * FROM `'. $this->gsitemap_table.'` WHERE `link` = "'. $this->sitemap_link .'" ORDER BY  `link` ASC')) {
           return Db::getInstance()->execute('DELETE FROM `'. $this->gsitemap_table.'` WHERE link = "'. $this->sitemap_link .'"') ? true : false;
        } else {
            return true;
        }
        return false;
    }

    public function createMainSitemapFile() : bool
    {
        $sitemaps = Db::getInstance()->ExecuteS('SELECT `link` FROM `' . _DB_PREFIX_ . 'gsitemap_sitemap` WHERE id_shop = ' . $this->context->shop->id);
        if (!$sitemaps) {
            return false;
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>';
        $xml_feed = new SimpleXMLElement($xml);
        foreach ($sitemaps as $sitemaps) {
            $sitemap = $xml_feed->addChild('sitemap');
            $sitemap->addChild('loc', $this->context->link->getBaseLink() . $sitemaps['link']);
            $sitemap->addChild('lastmod', date('c'));
        }
        file_put_contents($this->normalizeDirectory(_PS_ROOT_DIR_).$this->context->shop->id. '_index_sitemap.xml', $xml_feed->asXML());
        return true;
    }

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

    public function createFilterSitemap()
    {
        if($this->addSiteMapLink() == false)
        {
            return false;
        }
        return "hahah";
    }

    public function addVariableToConfigFile()
    {
        $variable = "";
        foreach(array(
                    'WEBO_FILTERSITEMAP_TOKEN' => bin2hex(random_bytes(30)),
                    'WEBO_FILTERSITEMAP_ACTIVE' => 1
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

}