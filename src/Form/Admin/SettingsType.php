<?php

declare(strict_types=1);

namespace PrestaShop\WeboSidemapGenerator\Form\Admin;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\AbstractType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\FormBuilderInterface;
use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Translation\TranslatorInterface;

class SettingsType extends TranslatorAwareType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('active', SwitchType::class, [
                'required' => false,
                'label' => $this->trans('Cache', 'Admin.Advparameters.Feature'),
                'help' => $this->trans('Should be enabled except for debugging.', 'Admin.Advparameters.Feature'),
            ]);
    }

}