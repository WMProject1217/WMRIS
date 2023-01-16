<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e179842b6ee0c90c35a00b42283d3a8
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'G' => 
        array (
            'GatewayWorker\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
        'GatewayWorker\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/gateway-worker/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1e179842b6ee0c90c35a00b42283d3a8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1e179842b6ee0c90c35a00b42283d3a8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1e179842b6ee0c90c35a00b42283d3a8::$classMap;

        }, null, ClassLoader::class);
    }
}