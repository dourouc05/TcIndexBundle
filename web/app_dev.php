<?php

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpFoundation\Request;

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

// Cache 
if(! file_exists($kernel->getCacheDir())) {
    mkdir($kernel->getCacheDir(), 0777); 
    var_dump('Répertoire du cache créé.');
} elseif(! is_writable($kernel->getCacheDir())) {
    chmod($kernel->getCacheDir(), 0777); 
    var_dump('Répertoire du cache chmodé.');
}

// Log
if(! file_exists($kernel->getLogDir())) {
    mkdir($kernel->getLogDir(), 0777); 
    var_dump('Répertoire des logs créé.');
} elseif(! is_writable($kernel->getLogDir())) {
    chmod($kernel->getLogDir(), 0777); 
    var_dump('Répertoire des logs chmodé.');
}

// Database directory
if(! file_exists($kernel->getCacheDir() . '/../../data')) {
    mkdir($kernel->getCacheDir() . '/../../data', 0777); 
    var_dump('Répertoire de la base créé.');
} elseif(! is_writable($kernel->getCacheDir() . '/../../data')) {
    chmod($kernel->getCacheDir() . '/../../data', 0777); 
    var_dump('Répertoire de la base chmodé.');
}

// Database
if(! file_exists($kernel->getCacheDir() . '/../../data/sqlite')) {
    touch($kernel->getCacheDir() . '/../../data/sqlite');
    chmod($kernel->getCacheDir() . '/../../data/sqlite', 0600);
    $dbh = new \PDO('sqlite:' . $kernel->getCacheDir() . '/../../data/sqlite');
    $dbh->exec('CREATE TABLE Category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, text CLOB DEFAULT NULL, path CLOB DEFAULT NULL, depth INTEGER NOT NULL, position INTEGER NOT NULL, disorder INTEGER NOT NULL);');
    $dbh->exec('CREATE UNIQUE INDEX UNIQ_FF3A7B97B548B0F ON Category (path);');
    $dbh->exec('CREATE INDEX IDX_FF3A7B97727ACA70 ON Category (parent_id);');
    $dbh->exec('CREATE TABLE Configuration (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, field VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL);');
    $dbh->exec('CREATE UNIQUE INDEX UNIQ_169CEE225BF54558 ON Configuration (field);');
    $dbh->exec('CREATE TABLE Item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, synopsis CLOB DEFAULT NULL, path CLOB DEFAULT NULL);');
    $dbh->exec('CREATE UNIQUE INDEX UNIQ_BF298A20B548B0F ON Item (path);');
    $dbh->exec('CREATE INDEX IDX_BF298A2012469DE2 ON Item (category_id);');
    $dbh->exec('CREATE TABLE fos_user (id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, PRIMARY KEY("id"));');
    $dbh->exec('CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical);');
    $dbh->exec('CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)');
    var_dump('Base de données créée.');
}

$kernel = new AppCache($kernel);
$kernel->handle(Request::createFromGlobals())->send();