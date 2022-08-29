<?php

if(!defined('_PS_VERSION_')){
    exit;
}

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
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminAdvancedParameters');
        $tab->active = 1;
        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[(int) $lang['id_lang']] = 'Side Map Generator';
        }
        if(!$tab->save()) {
            return false;
        }
        foreach(array(
                    'WEBO_FILTERSITEMAP_TOKEN' => bin2hex(random_bytes(30)),
                    'WEBO_FILTERSITEMAP_ACTIVE' => 1
                ) as $key => $val) {
            if(!Configuration::deleteByName($key, $val)) {
                return false;
            }
        }
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

}