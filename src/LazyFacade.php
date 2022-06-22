<?php
/**
 * Created by PhpStorm.
 * User: Hugh.Li
 * Date: 2021/4/18
 * Time: 10:31 下午.
 */

namespace HughCube\Laravel\ServiceSupport;

use BadMethodCallException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Facade as IlluminateFacade;

abstract class LazyFacade extends IlluminateFacade
{
    /**
     * @param  Application|mixed  $app
     * @return void
     */
    protected static function registerServiceProvider($app)
    {
        throw new BadMethodCallException('The implementation is required to register a service!');
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param  object|string  $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        if (null !== static::$app && !isset(static::$app[$name])) {
            static::registerServiceProvider(static::$app);
        }

        if (null !== static::$app) {
            return static::$resolvedInstance[$name] = static::$app[$name];
        }
    }
}
