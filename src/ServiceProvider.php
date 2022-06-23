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
            $this->app->configure($this->getFacadeAccessor());
        }
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app->singleton($this->getFacadeAccessor(), function ($app) {
            return $this->createFacadeRoot($app);
        });
    }

    abstract protected function getFacadeAccessor();

    /**
     * @param $app
     * @return mixed
     */
    abstract protected function createFacadeRoot($app);

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
