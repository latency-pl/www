<?php
// example
// http://localhost:8080/response.php?url=https%3A%2F%2Fsoftreck.com
require("apifunc.php");

header('Content-Type: application/json');

try {
    $url = $_GET['url'];
//    $url = "https://softreck.com";
    $url = urldecode($url);


    apifunc([
        'https://php.defjson.com/def_json.php',
    ], function () {

        global $url;


        $status = null;

        $headers = get_headers($url, 1);
        if (is_array($headers)) {
            $status = substr($headers[0], 9);
        }

//        var_dump($headers);
        echo def_json("", [
            "code" => $status,
            "url" => $url
        ]);
    });
} catch (Exception $e) {
    // Set HTTP response status code to: 500 - Internal Server Error
    echo def_json("", [
            "message" => $e->getMessage(),
            "url" => $url
        ]
    );
}
