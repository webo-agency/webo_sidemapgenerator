<?php
include(dirname(__FILE__) . '/../../config/config.inc.php');
if (!Tools::isPHPCLI()) {
    include(dirname(__FILE__) . '/../../init.php');
    if(!Configuration::get('WEBO_FILTERSITEMAP_TOKEN') == Tools::getValue('webo_token'))
    {
        die("");
    } else {
        $cron = Module::getInstanceByName('webo_sidemapgenerator');

        if($cron->active)
        {
            $cron->cronAction();
            echo '<script>console.log("200")</script>';
        }else {
            die("");
        }
    }
}
