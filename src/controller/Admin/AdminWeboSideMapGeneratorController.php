<?php

namespace PrestaShop\WeboSidemapGenerator\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminWeboSideMapGeneratorController extends FrameworkBundleAdminController
{

    public function configurationAction(Request $request)
    {
        $cron_link = _PS_BASE_URL_.'/modules/webo_sidemapgenerator/webosidemapgeneratorcron.php?webo_token='. $this->configuration->get('WEBO_FILTERSITEMAP_TOKEN');

        $form = $this->get('prestashop.module.webo_sidemapgenerator.form.configuration_data_handler');
        $configForm = $form->getForm();
        $configForm->handleRequest($request);
        if ($configForm->isSubmitted() && $configForm->isValid()) {
            /** You can return array of errors in form handler and they can be displayed to user with flashErrors */
            $errors = $form->save($configForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('admin_webo_sidemap');
            }

            $this->flashErrors($errors);
        }
        return $this->render('@Modules/webo_sidemapgenerator/templates/admin/DisplayAdminSettings.html.twig', [
            'mainConfigurationSideMap' => $configForm->createView(),
            'check_if_module_active' => $this->configuration->get("WEBO_FILTERSITEMAP_ACTIVE"),
            'cron_link' => $cron_link
        ]);
    }
}