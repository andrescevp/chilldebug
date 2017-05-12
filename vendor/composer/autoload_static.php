<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4b482476600088e492ecd9c30e8497a8
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'ChillDebug\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ChillDebug\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4b482476600088e492ecd9c30e8497a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4b482476600088e492ecd9c30e8497a8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}