<?php

declare(strict_types=1);

namespace PrestaShop\WeboSidemapGenerator\Form;

use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

final class ConfigurationDataProvider implements FormDataProviderInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
//    /**
//     * @var ConfigurationInterface
//     */
//    private $configuration;
//
//    public function __construct(ConfigurationInterface $configuration)
//    {
//        $this->configuration = $configuration;
//    }
//
//    public function getConfiguration()
//    {
//        return [
//            'active_module' => '1'
//        ];
//    }
//
//    public function updateConfiguration(array $configuration)
//    {
//        return [];
//    }
//
//    public function validateConfiguration(array $configuration)
//    {
//        return true;
//    }
        public function setData(array $data)
        {
            foreach ($data as $datum)
            {
                $this->configuration->set('WEBO_FILTERSITEMAP_ACTIVE', $datum['active_module']);
            }
        }

        public function getData()
        {
            return [
                'active_module' => $this->configuration->get('WEBO_FILTERSITEMAP_ACTIVE')
            ];
        }
}