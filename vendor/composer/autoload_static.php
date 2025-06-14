<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit20fad51902f91e7fd3039e016a6556b5
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Routes\\' => 7,
            'Responses\\' => 10,
            'Request\\' => 8,
        ),
        'M' => 
        array (
            'Models\\' => 7,
            'Middleware\\' => 11,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'C' => 
        array (
            'Controllers\\' => 12,
            'Classes\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Routes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/routes',
        ),
        'Responses\\' => 
        array (
            0 => __DIR__ . '/../..' . '/responses',
        ),
        'Request\\' => 
        array (
            0 => __DIR__ . '/../..' . '/request',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'Middleware\\' => 
        array (
            0 => __DIR__ . '/../..' . '/middleware',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controllers',
        ),
        'Classes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Classes\\DBORM' => __DIR__ . '/../..' . '/classes/DBORM.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Controllers\\UserController' => __DIR__ . '/../..' . '/controllers/UserController.php',
        'Firebase\\JWT\\BeforeValidException' => __DIR__ . '/..' . '/firebase/php-jwt/src/BeforeValidException.php',
        'Firebase\\JWT\\CachedKeySet' => __DIR__ . '/..' . '/firebase/php-jwt/src/CachedKeySet.php',
        'Firebase\\JWT\\ExpiredException' => __DIR__ . '/..' . '/firebase/php-jwt/src/ExpiredException.php',
        'Firebase\\JWT\\JWK' => __DIR__ . '/..' . '/firebase/php-jwt/src/JWK.php',
        'Firebase\\JWT\\JWT' => __DIR__ . '/..' . '/firebase/php-jwt/src/JWT.php',
        'Firebase\\JWT\\JWTExceptionWithPayloadInterface' => __DIR__ . '/..' . '/firebase/php-jwt/src/JWTExceptionWithPayloadInterface.php',
        'Firebase\\JWT\\Key' => __DIR__ . '/..' . '/firebase/php-jwt/src/Key.php',
        'Firebase\\JWT\\SignatureInvalidException' => __DIR__ . '/..' . '/firebase/php-jwt/src/SignatureInvalidException.php',
        'Middleware\\AuthMiddleware' => __DIR__ . '/../..' . '/middleware/AuthMiddleware.php',
        'Models\\DataRepositoryInterface' => __DIR__ . '/../..' . '/models/DataRepositoryInterface.php',
        'Models\\StudentRepository' => __DIR__ . '/../..' . '/models/StudentRepository.php',
        'Request\\Request' => __DIR__ . '/../..' . '/request/Request.php',
        'Request\\RequestInterface' => __DIR__ . '/../..' . '/request/RequestInterface.php',
        'Responses\\Response' => __DIR__ . '/../..' . '/responses/Response.php',
        'Routes\\RouteMatcher' => __DIR__ . '/../..' . '/routes/RouteMatcher.php',
        'Routes\\Router' => __DIR__ . '/../..' . '/routes/Router.php',
        'models\\UserRepository' => __DIR__ . '/../..' . '/models/UserRepository.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit20fad51902f91e7fd3039e016a6556b5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit20fad51902f91e7fd3039e016a6556b5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit20fad51902f91e7fd3039e016a6556b5::$classMap;

        }, null, ClassLoader::class);
    }
}
