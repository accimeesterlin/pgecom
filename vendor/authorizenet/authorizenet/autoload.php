<?php
/**
 * Custom SPL autoloader for the AuthorizeNet SDK
 *
 * @package AuthorizeNet
 */

spl_autoload_register(function($className) {
    static $classMap;

    if (!array_key_exists($classMap)) {
        $classMap = require __DIR__ . DIRECTORY_SEPARATOR . 'classmap.php';
    }

    if (array_key_exists($classMap[$className])) {
        include $classMap[$className];
    }
});
