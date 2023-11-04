<?php

declare(strict_types=1);

namespace StateMachine\Infra;

class ConfigurationManager
{
    private static $config;

    public static function load($file = 'config/app.php')
    {
        self::$config = include $file;
    }

    public static function get($key, $default = null)
    {
        return self::$config[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }
}
