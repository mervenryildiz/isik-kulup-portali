<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd17186d93a9f3134c5a7f99e0a33d5fc
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

        spl_autoload_register(array('ComposerAutoloaderInitd17186d93a9f3134c5a7f99e0a33d5fc', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd17186d93a9f3134c5a7f99e0a33d5fc', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd17186d93a9f3134c5a7f99e0a33d5fc::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}