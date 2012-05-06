TCIndexBundle
=============

Three entitites at the root of everything: 
### Category: a category of items, projects or articles or author presentation e.g.
### Item: an item, a projet or article e.g. 

The resulting page will look like this: 

    <template>
	<category 1>
		<text>
	<category 2>
		<text>
		<item 1>
		<item 2>
		<category 3>
			<text>
			<item 1>
			<item 2>
			<item 3>
			<item 4>
	<category 3>
		<item 1>
		<item 2>
	<template>

Current SQL queries to create the database (SQLite format, may be adapted for other DBMS): 
        CREATE TABLE Category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, text CLOB DEFAULT NULL, path CLOB DEFAULT NULL, depth INTEGER NOT NULL, position INTEGER NOT NULL, disorder INTEGER NOT NULL);
        CREATE UNIQUE INDEX UNIQ_FF3A7B97B548B0F ON Category (path);
        CREATE INDEX IDX_FF3A7B97727ACA70 ON Category (parent_id);
        CREATE TABLE Configuration (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, field VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL);
        CREATE UNIQUE INDEX UNIQ_169CEE225BF54558 ON Configuration (field);
        CREATE TABLE Item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, synopsis CLOB DEFAULT NULL, path CLOB DEFAULT NULL);
        CREATE UNIQUE INDEX UNIQ_BF298A20B548B0F ON Item (path);
        CREATE INDEX IDX_BF298A2012469DE2 ON Item (category_id);
        CREATE TABLE fos_user (id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, PRIMARY KEY("id"));
        CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical);
        CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)

Current limitations: 
### It is not possible to sort or search in the categories list (the model should be updated, as 
    soon as AdminGenerator is updated to support tree views with Doctrine). 
### When importing, if there is any submarkup, it will be ignored (a synopsis with links will have
    no links, their content will be completely lost). 
### The database must be created manually (use the development front controller, which will check 
    whether the folders are writable when needed - cache and logs). 