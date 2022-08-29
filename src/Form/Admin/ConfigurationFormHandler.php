<?php

namespace Prestashop\Module\WeboSidemapGenerator\Form;

use PrestaShop\PrestaShop\Adapter\Feature\CombinationFeature;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;

final class ConfigurationFormHandler
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var CombinationFeature
     */
    private $combinationFeature;

    /**
     * @var FormDataProviderInterface
     */
    private $formDataProvider;

    public function __construct(FormFactoryInterface $formFactory, CombinationFeature $combinationFeature, FormFactoryInterface $formDataProvider)
    {
        $this->formFactory = $formFactory;
        $this->combinationFeature = $combinationFeature;
        $this->formDataProvider = $formDataProvider;
    }

    public function getForm()
    {
        $formBuilder = $this->formFactory->createBuilder()
            ->add('settings', SettingsType::class)
            ->setData($this->formDataProvider->getData());
        return $formBuilder->setData($formBuilder->getData())->getForm();
    }

    public function save(array $data)
    {
        return $this->formDataProvider->setData($data);
    }

}