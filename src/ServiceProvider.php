<?php
/**
 * Created by PhpStorm.
 * User: Hugh.Li
 * Date: 2021/4/18
 * Time: 10:32 下午.
 */

namespace HughCube\Laravel\ServiceSupport;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Boot the provider.
     */
    public function boot()
    {
        if ($this->app instanceof LumenApplication) {
            $this->app->configure($this->getPackageFacadeAccessor());
        }
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app->singleton($this->getPackageFacadeAccessor(), function ($app) {
            return $this->createPackageFacadeRoot($app);
        });
    }

    abstract protected function getPackageFacadeAccessor();

    /**
     * @param $app
     *
     * @return mixed
     */
    abstract protected function createPackageFacadeRoot($app);

//    /**
//     * register the provider config.
//     */
//    protected function registerConfig($source)
//    {
//        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
//            $source = realpath(dirname(__DIR__).'/config/config.php');
//            $this->publishes([$source => config_path(sprintf("%s.php", $this->getFacadeAccessor()))]);
//        }
//
//        if ($this->app instanceof LumenApplication) {
//            $this->app->configure($this->getFacadeAccessor());
//        }
//    }
}
