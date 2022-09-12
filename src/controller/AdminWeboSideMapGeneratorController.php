<?php

namespace PrestaShop\WeboSidemapGenerator\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminWeboSideMapGeneratorController extends FrameworkBundleAdminController
{
    public function demoAction()
    {
        // you can also retrieve services directly from the container
        $cache = $this->container->get('doctrine.cache');

        return $this->render('@Modules/webo_sidemapgenerator/templates/admin/DisplayAdminSettings.html.twig');
    }
}