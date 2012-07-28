Total Annihilation Ranking [![Build Status](https://secure.travis-ci.org/augustohp/taranking.png?branch=match)](http://travis-ci.org/augustohp/taranking)
==========================

A basic API for ranking players in [*Total Annihilation*](http://tauniverse.com) games.
Although an API is availiable, it also contains a basic implementation for (basic) usage.

Requirements
------------

* PHP >= 5.3.10
* PHP Modules
    * APC (used by Doctrine)
    * mbstring (used by Respect/Validation)
    * zip (used by Symfony Components)
    * timezonedb
* Database extension (SQLite is default)

Instalation
-----------

This application *should* run on any Operating System, but it was only tested
on Mac OSX 10.6.

It uses an **.htaccess** file for *URL Rewriting* in *Apache* web server, if
you are going to use any other, please: be sure that all requests are redirected
to **/public/index.php** unless the given request resource exists in the file
system, inside the */public* folder.

The *Document Root* should be pointed to */public*.

To install the project dependencies (they are *necessary*), run 
[Composer's](http://getcomposer.org) installation command inside the project
directory as follow:

    $ cd <project root folder>
    $ chmod a+x bin/composer.phar bin/doctrine
    $ bin/composer.phar install

This application needs a database connection, without configuration it creates
a SQLite database into the root directory of the application called *database.sqlite*.

Database configuration can be set via [Enviroment Variables](https://en.wikipedia.org/wiki/Environment_variables)
and they are:

* RANKING_DB_HOST: The host to connect
* RANKING_DB_USER: Username used to connect to the databse
* RANKING_DB_PASSWD: Password of the before mentioned username
* RANKING_DB_NAME: The database name that are going to be used
* RANKING_DB_DRIVER: The [php extension](http://br2.php.net/manual/en/refs.database.php) that are going to be used for connection

Project Structure
-----------------

This project uses several components in order to work correctly, providing an
easy, fast and scalable way of development, they are:

* [Respect/Rest](http://github.com/Respect/Rest): Front Controller of the application, know what to do with all requests
* [Respect/Validation](http://github.com/Respect/Validation): Used for validation information inside the project
* [Respect/Config](http://github.com/Respect/Config): Easy dependecy injection container (Used to serve Doctrine's EntityManager)
* [Doctrine](http://doctrine-project.org): Object Relation Mapper and Database Abstraction Layer (both are used)
* [Twig](http://twig.sensiolabs.org): A simple templating system
* [PHPUnit](http://phpunit.de): Tool for unit testing the code (used by developers only)
* [Composer](http://getcomposer.org): Dependency management, maintains all the above components installed and up-to-date

The directory structure is very widely-used:

* **bin**: commands availiable to be executed (Ex: Composer, Doctrine)
* **conf**: INI files that are used by [Respect/Config](http://github.com/Respect/Config)
* **public**: Document root. Contains an index.php with the routes (see [Respect/Rest](http://github.com/Respect/Rest)) and the resources (images, stylesheets, (java)scripts)
* **src**: The classes of this project
* **tests**: Unit tests for the project
* **vendor**: Created by composer, it is where all the components's code live
* **templates**: HTML templates for the application (used by Twig)