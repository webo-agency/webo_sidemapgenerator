<?php

namespace Prestashop\Module\WeboSidemapGenerator\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigurationFormHandler extends TranslatorAwareType
{
    public function __construct(TranslatorInterface $translator, array $locales)
    {
        parent::__construct($translator, $locales);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Title',
                    'help' => 'Throws error if length is > 50 or text contains <>={}',
                    'constraints' => [
                        new TypedRegex([
                            'type' => 'generic_name',
                        ]),
                        new Length([
                            'max' => 50,
                        ]),
                    ],
                ]
            );
    }

//    /**
//     * @var FormFactoryInterface
//     */
//    private $formFactory;
//
//    /**
//     * @var CombinationFeature
//     */
//    private $combinationFeature;
//
//    /**
//     * @var FormDataProviderInterface
//     */
//    private $formDataProvider;
//
//    public function __construct(FormFactoryInterface $formFactory, CombinationFeature $combinationFeature, FormFactoryInterface $formDataProvider)
//    {
//        $this->formFactory = $formFactory;
//        $this->combinationFeature = $combinationFeature;
//        $this->formDataProvider = $formDataProvider;
//    }
//
//    public function getForm()
//    {
//        $formBuilder = $this->formFactory->createBuilder()
//            ->add('settings', SettingsType::class)
//            ->setData($this->formDataProvider->getData());
//        return $formBuilder->setData($formBuilder->getData())->getForm();
//    }
//
//    public function save(array $data)
//    {
//        return $this->formDataProvider->setData($data);
//    }

}