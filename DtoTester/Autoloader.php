<?php

namespace DtoTester;

class Autoloader
{
    /**
     * Autoload DTO classes namespaced based on their path (PSR mode)
     *
     * @return bool
     */
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}
