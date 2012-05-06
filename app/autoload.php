<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array(__DIR__.'/../vendor_light/symfony/src', __DIR__.'/../vendor_light/bundles'),
    'Sensio'           => __DIR__.'/../vendor_light/bundles',
    'JMS'              => __DIR__.'/../vendor_light/bundles',
    'Doctrine\\Bundle' => __DIR__.'/../vendor_light/bundles',
    'Doctrine\\Common\\DataFixtures' => __DIR__.'/../vendor_light/doctrine-fixtures/lib',
    'Doctrine\\Common' => __DIR__.'/../vendor_light/doctrine-common/lib',
    'Doctrine\\DBAL'   => __DIR__.'/../vendor_light/doctrine-dbal/lib',
    'Doctrine'         => __DIR__.'/../vendor_light/doctrine/lib',
    'Monolog'          => __DIR__.'/../vendor_light/monolog/src',
    'Assetic'          => __DIR__.'/../vendor_light/assetic/src',
    'Metadata'         => __DIR__.'/../vendor_light/metadata/src',
    'Admingenerator'   => array(__DIR__.'/../src', __DIR__.'/../vendor_light/bundles'),
    'Knp'              => __DIR__.'/../vendor_light/bundles',
    'Knp\\Menu'        => __DIR__.'/../vendor_light/KnpMenu/src',
    'WhiteOctober\\PagerfantaBundle' => __DIR__.'/../vendor_light/bundles',
    'Pagerfanta'       => __DIR__.'/../vendor_light/pagerfanta/src',
    'TwigGenerator'    => __DIR__.'/../vendor_light/twig-generator/src',
    'CG'               => __DIR__.'/../vendor_light/cg-library/src',
    'FOS'              => __DIR__.'/../vendor_light/bundles',
));
$loader->registerPrefixes(array(
    'Twig_Extensions_' => __DIR__.'/../vendor_light/twig-extensions/lib',
    'Twig_'            => __DIR__.'/../vendor_light/twig/lib',
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor_light/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array(__DIR__.'/../vendor_light/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}

$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile(__DIR__.'/../vendor_light/doctrine/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor_light/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor_light/swiftmailer/lib/swift_init.php');

