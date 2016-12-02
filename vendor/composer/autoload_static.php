<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4e92a96dd7d4dec92bc7f743e36fe04a
{
    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'core\\' => 5,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Twig_' => 
            array (
                0 => __DIR__ . '/..' . '/twig/twig/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4e92a96dd7d4dec92bc7f743e36fe04a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4e92a96dd7d4dec92bc7f743e36fe04a::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit4e92a96dd7d4dec92bc7f743e36fe04a::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}