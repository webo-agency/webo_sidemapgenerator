<?php

declare(strict_types=1);

namespace PrestaShop\WeboSidemapGenerator\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class configurationDataConfiguration implements DataConfigurationInterface
{
    public const WEBO_MODULE_ACTIVATION = 'WEBO_FILTERSITEMAP_ACTIVE';
    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function validateConfiguration(array $configuration):bool
    {
        return true;
    }

    public function updateConfiguration(array $configuration): array
    {

        $this->configuration->set(static::WEBO_MODULE_ACTIVATION, $configuration['active_module']);
        return [];
    }

    public function getConfiguration()
    {
        $return = [];

        if($configActiveModule = $this->configuration->get(static::WEBO_MODULE_ACTIVATION))
        {
            $return['active_module'] = $configActiveModule;
        }
        return $return;
    }
}