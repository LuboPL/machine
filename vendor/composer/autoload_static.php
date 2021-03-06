<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd1ffe2c91ecc80eb91428b24da23f91e
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'VendingMachine\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'VendingMachine\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd1ffe2c91ecc80eb91428b24da23f91e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd1ffe2c91ecc80eb91428b24da23f91e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd1ffe2c91ecc80eb91428b24da23f91e::$classMap;

        }, null, ClassLoader::class);
    }
}
