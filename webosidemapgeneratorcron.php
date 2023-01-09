<?php
include(dirname(__FILE__) . '/../../config/config.inc.php');
if (!Tools::isPHPCLI()) {
    include(dirname(__FILE__) . '/../../init.php');
    if(!Configuration::get('WEBO_FILTERSITEMAP_TOKEN') == Tools::getValue('webo_token'))
    {
        //change to 404
        die(404);
    } else {
        $cron = Module::getInstanceByName('webo_sidemapgenerator');

        if($cron->active)
        {
            $cron->cronAction();
        }else {
            die('Webo Sidemap generator is not active');
        }
    }
}
