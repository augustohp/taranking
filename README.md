Total Annihilation Ranking
==========================

A basic API for ranking players in [*Total Annihilation*](http://tauniverse.com) games.
Although an API is availiable, it also contains a basic implementation for (basic) usage.

Requirements
------------

* PHP >= 5.3.10
* SQLite 3 (php module)

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
