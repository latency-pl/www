# www
Check Latency connection in ms

Opóźnienie z ogólnego punktu widzenia to opóźnienie czasowe między przyczyną a skutkiem jakiejś fizycznej zmiany obserwowanego systemu.

TODO:

https://github.com/geerlingguy/Ping

composer require geerlingguy/ping
    
    $host = 'www.example.com';
    $ping = new \JJG\Ping($host);
    $latency = $ping->ping();
    if ($latency !== false) {
    print 'Latency is ' . $latency . ' ms';
    }
    else {
    print 'Host could not be reached.';
    }
# API for latency, based on PHP
Check domains , test connection, screen,

+ [github.com/latency-pl](https://github.com/latency-pl)
+ [latency-pl.pl](https://latency-pl.pl/)

# Install

## Composer
install packages

    php composer.phar require "jsmitty12/phpwhois":"dev-master"
    php composer.phar require algo26-matthias/idna-convert

## github

https://github.com/latency-pl/www

## GIT

https://github.com/latency-pl/www.git

    git clone https://github.com/latency-pl/www.git


# project URL-s

## for index with own json files

+ [localhost:8080](http://localhost:8080/)


    curl http://localhost:8080/index.php


## GET Request

localhost

[http://localhost:8080/get.php?domain=apiprogram.com](http://localhost:8080/get.php?domain=apiprogram.com)

    curl http://localhost:8080/index.php?domain=apiprogram.com


# php serve

    php -S 0.0.0.0:8080

    http://localhost:8080/index.php


# START

http://localhost:8080/index.php?domain=softreck.com


# WWW

https://www.latency-pl.pl/index.php?domain=softreck.com

+ [edit](https://github.com/latency-pl/www/edit/main/README.md)