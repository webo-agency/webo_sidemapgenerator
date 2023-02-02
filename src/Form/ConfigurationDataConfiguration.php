<?php

declare(strict_types=1);

namespace PrestaShop\WeboSidemapGenerator\Form;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class configurationDataConfiguration implements DataConfigurationInterface
{
    public const WEBO_MODULE_ACTIVATION = 'WEBO_FILTERSITEMAP_ACTIVE';
    public const WEBO_INDEXED_MODULE = 'WEBO_FILTERSITEMAP_SITEMAPINDEX';
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
        $this->configuration->set(static::WEBO_INDEXED_MODULE, $configuration['check_if_indexed']);
        return [];
    }

    public function getConfiguration():array
    {
        $return = [];

        if($configActiveModule = $this->configuration->get(static::WEBO_MODULE_ACTIVATION))
        {
            $return['active_module'] = $configActiveModule;
        }
        if($configIndexedModule = $this->configuration->get(static::WEBO_INDEXED_MODULE))
        {
            $return['check_if_indexed'] = $configIndexedModule;
        }
        return $return;
    }
}