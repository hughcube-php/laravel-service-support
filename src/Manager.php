<?php
/**
 * Created by PhpStorm.
 * User: Hugh.Li
 * Date: 2021/4/20
 * Time: 4:19 下午.
 */

namespace HughCube\Laravel\ServiceSupport;

use Closure;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Support\Manager as IlluminateManager;
use InvalidArgumentException;

/**
 * @property callable|ContainerContract|null $container
 * @property callable|Repository|null        $config
 */
abstract class Manager extends IlluminateManager
{
    /**
     * @param callable|ContainerContract|null $container
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function extend($driver, Closure $callback)
    {
        return parent::extend($driver, $callback->bindTo($this, $this));
    }

    /**
     * Call a custom driver creator.
     *
     * @param string $driver
     *
     * @return mixed
     */
    protected function callCustomCreator($driver)
    {
        return $this->customCreators[$driver]($this, $driver);
    }

    /**
     * @return ContainerContract
     */
    public function getContainer(): ContainerContract
    {
        if (!property_exists($this, 'container') || null === $this->container) {
            return IlluminateContainer::getInstance();
        }

        if (null === $this->container && is_callable($this->container)) {
            return call_user_func($this->container);
        }

        return $this->container;
    }

    /**
     * @throws
     *
     * @return Repository
     *
     * @phpstan-ignore-next-line
     */
    protected function getContainerConfig(): Repository
    {
        if (!property_exists($this, 'config') || null === $this->config) {
            return $this->getContainer()->make('config');
        }

        if (is_callable($this->config)) {
            return call_user_func($this->config);
        }

        return $this->config;
    }

    /**
     * @param null|string|int $name
     * @param mixed           $default
     *
     * @return array|mixed
     */
    protected function getPackageConfig($name = null, $default = null)
    {
        $key = sprintf('%s%s', $this->getPackageFacadeAccessor(), (null === $name ? '' : ".$name"));

        return $this->getContainerConfig()->get($key, $default);
    }

    /**
     * Get the configuration for a client.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    protected function configuration(string $name): array
    {
        $name = $name ?: $this->getDefaultDriver();
        $config = $this->getPackageConfig(sprintf('%s.%s', $this->getDriversConfigKey(), $name));

        if (null === $config) {
            throw new InvalidArgumentException(sprintf(
                "%s %s[{$name}] not configured.",
                $this->getPackageFacadeAccessor(),
                $this->getDriversConfigKey()
            ));
        }

        return array_merge($this->getDriverDefaultConfig(), $config);
    }

    /**
     * @inheritdoc
     */
    public function getDefaultDriver(): string
    {
        return $this->getPackageConfig('default', 'default');
    }

    /**
     * @inheritdoc
     */
    protected function createDriver($driver)
    {
        return $this->makeDriver($this->configuration($driver));
    }

    /**
     * @return array
     */
    protected function getDriverDefaultConfig(): array
    {
        return $this->getPackageConfig('defaults', []);
    }

    public function client($driver = null)
    {
        return $this->driver($driver);
    }

    protected function getDriversConfigKey(): string
    {
        return 'drivers';
    }

    abstract protected function makeDriver(array $config);

    abstract protected function getPackageFacadeAccessor();
}
