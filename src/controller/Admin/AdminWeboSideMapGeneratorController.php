<?php

namespace PrestaShop\WeboSidemapGenerator\Controller\Admin;

use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShop\WeboSidemapGenerator\Form\Admin\SettingsType;

class AdminWeboSideMapGeneratorController extends FrameworkBundleAdminController
{
    public function demoAction()
    {
        $test = $this->getSidemapSettings()->getForm();
        return $this->render('@Modules/webo_sidemapgenerator/templates/admin/DisplayAdminSettings.html.twig', [
            'mainConfigurationSideMap' => $test->createView()
        ]);
    }

    public function getSidemapSettings(): FormHandlerInterface
    {
        return $this->get('prestashop.module.webo_sidemapgenerator.form.settings');
    }
}