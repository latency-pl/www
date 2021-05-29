<?php
// http://localhost:8080/index.php
error_reporting(E_ERROR | E_PARSE);

require("load_func.php");
$html = '';


if (empty($_POST["domains"])) {
    $_POST["domains"] = "softreck.com";
}

try {


    if (isset($_POST["latency"])) {

        load_func([
            'https://php.letjson.com/let_json.php',
            'https://php.defjson.com/def_json.php',
            'https://php.eachfunc.com/each_func.php',
            'https://domain.phpfunc.com/get_domain_by_url.php',
            'https://domain.phpfunc.com/clean_url.php',
            'https://domain.phpfunc.com/clean_url_multiline.php',

        ], function () {

            // Clean URL
            $domains = clean_url_multiline($_POST["domains"]);

            if (empty($domains)) {
                throw new Exception("domain list is empty");
            }

            $domain_list = array_values(array_filter(explode(PHP_EOL, $domains)));

//        var_dump($domain_list);
//        die;
            if (empty($domain_list)) {
                throw new Exception("domain list is empty");
            }

            $domain_nameserver_list = each_func($domain_list, function ($url) {

                if (empty($url)) return null;

                $url = clean_url($url);

                if (empty($url)) return null;

                if (!(strpos($url, "http://") === 0) && !(strpos($url, "https://") === 0)) {
                    $url = "http://" . $url;
                }

                $domain = get_domain_by_url($url);

                return "
 <div>
    <a href='$url' target='_blank'> $domain</a> 
    -
    <a class='latency' href='https://www.latency.pl/latency.php?domain=$domain' target='_blank'> $domain </a>
</div>
            ";
            });

            global $html;

            $html = implode("<br>", $domain_nameserver_list);
//        var_dump($domain_nameserver_list);
//        var_dump($screen_shot_image);

        });
    }


} catch (Exception $e) {
    // Set HTTP response status code to: 500 - Internal Server Error
    $html = $e->getMessage();
}


function response_status($url)
{
    $headers = get_headers($url, 1);

    if (is_array($headers)) {
        return $headers[0];
//        return substr($headers[0], 9);
    }

    return domain_exist($url);
}


function domain_exist($url)
{
    $domain = get_domain_by_url($url);

//    if (checkdnsrr($domain , 'ANY')) {
//    if (gethostbyname($domain) != $domain ) {
    return gethostbynamel($domain);
    if (gethostbynamel($domain) != $domain) {
        return "DNS Record found";
    } else {
        return "NO DNS Record found";
    }
}


?>


<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>multi domains latency Test</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
<div class="container box">

    <h2 class="center">latency.pl</h2>
    <p class="center">Test your domains and webservices latency</p>
    <hr>
    <form method="post">
        <div class="form-group">
            <label>Enter domain names, line by line</label>
            <br>
            <textarea name="domains" cols="55" rows="20"><?php echo $_POST["domains"] ?></textarea>
        </div>
        <br/>
        <input type="submit" name="latency" value="latency" class="btn btn-info btn-lg"/>
    </form>
    <br/>
    <?php
    echo $html;
    ?>
</div>
<div style="clear:both"></div>
<br/>
<hr>
<div class="center">
    <div>
        API latency:
        <a href="http://www.latency.pl" target='_blank'>latency </a>
    </div>

    <div>
        DEV:
        <a href="https://github.com/webtest-pl/www" target='_blank'>source code</a>
        |
        <a href="https://webtest.pl/" target='_blank'> production </a>
        |
        <a href="http://localhost:8080/" target='_blank'> localhost </a>

    </div>

    <div>
        Supported by:
        <a href="https://softreck.com" target='_blank'>softreck.com</a>
        |
        <a href="https://softreck.pl" target='_blank'>softreck.pl</a>
        |
        <a href="https://www.webstream.dev" target='_blank'>webstream.dev</a>
        |
        <a href="https://www.apifunc.com" target='_blank'>apifunc.com</a>

    </div>
</div>

<script>
    $('a.latency').each(function () {
        var atext = $(this);
        var url = atext.attr('href');
        var jqxhr = $.ajax(url)
            .done(function (result) {
                console.log(result);
                console.log(atext);
                console.log(result.registered);
                atext.addClass("active");
                atext.html(result.registered);
            });
    });
</script>


<style>
    a.domain {
        color: gray;
        text-decoration: none;
    }

    a.domain.active {
        color: #222333;
        text-decoration: none;
    }

    img.img-thumbnail {
        height: 300px;
    }

    iframe {
        height: 300px;
        width: 600px;
    }

    .box {
        width: 100%;
        max-width: 720px;
        margin: 0 auto;
    }

    .center {
        margin: auto;
        max-width: 720px;
    }

    .center div {
    }


</style>
</body>
</html>
