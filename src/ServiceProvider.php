<?php
/**
 * Created by PhpStorm.
 * User: Hugh.Li
 * Date: 2021/4/18
 * Time: 10:32 下午.
 */

namespace HughCube\Laravel\ServiceSupport;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

abstract class ServiceProvider extends IlluminateServiceProvider
{
    abstract protected function getFacadeAccessor();

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
