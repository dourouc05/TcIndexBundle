<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('prod', true);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);

// A bit of caching
if(function_exists('xcache_get')) {
    $config = new \Doctrine\ORM\Configuration();
    $config->setQueryCacheImpl(new \Doctrine\Common\Cache\XcacheCache());
    $config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ApcCache());
}

$kernel->handle(Request::createFromGlobals())->send();
