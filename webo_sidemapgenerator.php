<?php

if(!defined('_PS_VERSION_')){
    exit;
}

class webo extends Module
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
        if(parent::install()) {
            return true;
        }
        return false;
    }

    public function uninstall()
    {
        if(parent::uninstall()) {
            return true;
        }
        $this->_errors[] = $this->trans('There was an error during the uninstallation. Please see documentation <a href="https://github.com/webo-agency/webo_taghint">here</a>', array(), 'Modules.Webo_RemoveAllItem.Admin');
        return false;
    }
}