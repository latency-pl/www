<?php
/*
 * https://www.wolnadomena.pl/registered.php?domain=softreck.com
 * http://localhost:8080/registered.php?domain=softreck.com
 */

// Load composer framework
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require(__DIR__ . '/vendor/autoload.php');
}

use phpWhois\Whois;

require("load_func.php");

header('Content-Type: application/json');

try {
//    $domain = $_GET['domain'];
    $domain = 'softreck.com';

    if (empty($domain)) {
        throw new Exception("domain is empty");
    }

    $domain = strtolower($domain);

    load_func([
        'https://php.defjson.com/def_json.php'
    ], function () {

        global $domain;
//        $ping = 0;
        $ping =  ping($domain, 80, 10);


        echo def_json("", [
            "ping" => $ping,
            "domain" => $domain
        ]);
    });

} catch (Exception $e) {
    // Set HTTP response status code to: 500 - Internal Server Error
    echo def_json("", [
            "message" => $e->getMessage(),
            "domain" => $domain
        ]
    );
}

function ping($host, $port, $timeout)
{
    $tB = microtime(true);
    $fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
    if (!$fP) {
        return "down";
    }
    $tA = microtime(true);
    return round((($tA - $tB) * 1000), 0) . " ms";
}

