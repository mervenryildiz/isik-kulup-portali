<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInite35ce3675fa9994c1ae8fc46cda9ba13
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInite35ce3675fa9994c1ae8fc46cda9ba13', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInite35ce3675fa9994c1ae8fc46cda9ba13', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInite35ce3675fa9994c1ae8fc46cda9ba13::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
