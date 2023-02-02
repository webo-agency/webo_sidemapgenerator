<?php
declare(strict_types=1);

namespace PrestaShop\WeboSidemapGenerator\Form;

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigurationType extends TranslatorAwareType
{

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('active_module', SwitchType::class, [
                'choices' => [
                    'Disable' => 0,
                    'Enable' => 1,
                ],
                'required' => false,
                'label' => 'Enable module',
                'help' => 'Confirm whether the module is to be active',
            ])
            ->add('check_if_indexed', SwitchType::class, [
                'choices' => [
                    'Disable' => 0,
                    'Enable' => 1,
                ],
                'required' => false,
                'label' => 'Module must be indexed',
                'help' => 'If module is Google Indexed'
            ]);
    }

}