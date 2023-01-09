<?php

namespace PrestaShop\WeboSidemapGenerator\Controller\Admin;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShop\WeboSidemapGenerator\Form\ConfigurationType;
use Symfony\Component\HttpFoundation\Request;

class AdminWeboSideMapGeneratorController extends FrameworkBundleAdminController
{

    public function configurationAction(Request $request)
    {
        $form = $this->createForm(ConfigurationType::class);
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid() && !is_null($form->getData()['active_module']))
//        {
//            Configuration::updateValue('WEBO_FILTERSITEMAP_ACTIVE', $form->getData()['active_module']);
//            $this->addFlash('success', $this->trans('no co ty.', 'Modules.Demodoctrine.Admin'));
//        }
        return $this->render('@Modules/webo_sidemapgenerator/templates/admin/DisplayAdminSettings.html.twig', [
            'mainConfigurationSideMap' => $form->createView(),
            'requestes' => $request->get('active')
        ]);
    }
}