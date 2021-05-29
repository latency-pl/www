<?php

// https://loadfunc.github.io/php/load_func.php
// curl https://loadfunc.github.io/php/load_func.php --output load_func.php

/**
 * @param array $func_url_array
 * @param $callback
 * @param string $local_path
 * @return mixed
 *
 * @throws Exception
 */
function load_func(array $func_url_array, $callback, $local_path = '.load_func')
{
    /** @var string $func_url */
    foreach ($func_url_array as $func_url) {

        $file_name = basename($func_url);

        // IF exist in current folder
        if (file_exists($file_name)) {
            include_once($file_name);
            continue;
        }

        // If exist PATH
        if (!empty($local_path)) {
            if (!file_exists($local_path)) {
                if (!mkdir($local_path) && !is_dir($local_path)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $local_path));
                }
            }
            $local_path = $local_path . DIRECTORY_SEPARATOR;
        }

        // FILE
        $path = $local_path . $file_name;

        // download if not exist
        if (!file_exists($path)) {

            // check if URL exist
            if (!url_exists($func_url)) {
                throw new Exception("The Url: " . $func_url . " not exist ");
            }

            // check if File is writeable
            $out = fopen($path, "wb");
            if ($out == FALSE) {
                throw new Exception("File not opened");
            }

//            echo "::url: ". $func_url;


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_FILE, $out);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $func_url);

            curl_exec($ch);

            if (!empty(curl_error($ch))) {
                throw new Exception("Error for url: " . $func_url . " : " . curl_error($ch));
            }

            curl_close($ch);
            //fclose($handle);
        }

        //    if(!@include($path)) throw new Exception("Failed to include 'script.php'");

        if (!file_exists($path)) {
            throw new Exception("File: " . $path . " not exist");
        }

        // include
        include_once($path);
    }

    return $callback($func_url_array);
}

/**
 * https://loadfunc.github.io/php/load_func.php
 *
 * Class LetJson
 */
class LoadFunc
{
    // IN
    public $func_url_array = [];

    // OUT
    public $val;


    /**
     * LoadFunc constructor.
     * @param array $func_url_array
     */
    public function __construct(array $func_url_array)
    {
        $this->func_url_array = $func_url_array;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function exec()
    {
        $local_path = '';
        foreach ($this->func_url_array as $func_url) {
            $file_name = basename($func_url);
//        var_dump($func_name);
            $path = $local_path . $file_name;

            // download if not exist
            if (!file_exists($path)) {

                $out = fopen($path, "wb");
                if ($out == FALSE) {
                    throw new Exception("File not opened");
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_FILE, $out);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_URL, $func_url);

                curl_exec($ch);

                if (!empty(curl_error($ch))) {
                    throw new Exception("Error is : " . curl_error($ch));
                }

                curl_close($ch);
                //fclose($handle);
            }

            //    if(!@include($path)) throw new Exception("Failed to include 'script.php'");

            if (!file_exists($path)) {
                throw new Exception("File: " . $path . " not exist");
            }

            // include
            include_once($path);
        }

//        return $callback($func_url_array);
        return $this->val = $this->func_url_array;
    }

    /*
        public function __toString()
        {
            try
            {
                return (string) $this->val;
            }
            catch (Exception $exception)
            {
                return '';
            }
        }
    */

    function each($callback)
    {
        foreach ($this->val as $item) {
            $callback($item);
        }
    }
}


function url_exists($url)
{
//    if (isValidUrl($url)) {
//        return true;
//    }
    if (curl_init($url) === false) {
        return false;
    }

    $headers = @get_headers($url);
    if (strpos($headers[0], '200') === false) {
        return false;
    }

    return true;
}

function isValidUrl($url)
{
    // first do some quick sanity checks:
    if (!$url || !is_string($url)) {
        return false;
    }
    // quick check url is roughly a valid http request: ( http://blah/... )
    if (!preg_match('/^http(s)?:\/\/[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url)) {
        return false;
    }
    // the next bit could be slow:
    if (getHttpResponseCode_using_curl($url) != 200) {
//      if(getHttpResponseCode_using_getheaders($url) != 200){  // use this one if you cant use curl
        return false;
    }
    // all good!
    return true;
}

function getHttpResponseCode_using_curl($url, $followredirects = true)
{
    // returns int responsecode, or false (if url does not exist or connection timeout occurs)
    // NOTE: could potentially take up to 0-30 seconds , blocking further code execution (more or less depending on connection, target site, and local timeout settings))
    // if $followredirects == false: return the FIRST known httpcode (ignore redirects)
    // if $followredirects == true : return the LAST  known httpcode (when redirected)
    if (!$url || !is_string($url)) {
        return false;
    }
    $ch = @curl_init($url);
    if ($ch === false) {
        return false;
    }
    @curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
    @curl_setopt($ch, CURLOPT_NOBODY, true);    // dont need body
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // catch output (do NOT print!)
    if ($followredirects) {
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        @curl_setopt($ch, CURLOPT_MAXREDIRS, 10);  // fairly random number, but could prevent unwanted endless redirects with followlocation=true
    } else {
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    }
//      @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);   // fairly random number (seconds)... but could prevent waiting forever to get a result
//      @curl_setopt($ch, CURLOPT_TIMEOUT        ,6);   // fairly random number (seconds)... but could prevent waiting forever to get a result
//      @curl_setopt($ch, CURLOPT_USERAGENT      ,"Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1");   // pretend we're a regular browser
    @curl_exec($ch);
    if (@curl_errno($ch)) {   // should be 0
        @curl_close($ch);
        return false;
    }
    $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); // note: php.net documentation shows this returns a string, but really it returns an int
    @curl_close($ch);
    return $code;
}

function getHttpResponseCode_using_getheaders($url, $followredirects = true)
{
    // returns string responsecode, or false if no responsecode found in headers (or url does not exist)
    // NOTE: could potentially take up to 0-30 seconds , blocking further code execution (more or less depending on connection, target site, and local timeout settings))
    // if $followredirects == false: return the FIRST known httpcode (ignore redirects)
    // if $followredirects == true : return the LAST  known httpcode (when redirected)
    if (!$url || !is_string($url)) {
        return false;
    }
    $headers = @get_headers($url);
    if ($headers && is_array($headers)) {
        if ($followredirects) {
            // we want the last errorcode, reverse array so we start at the end:
            $headers = array_reverse($headers);
        }
        foreach ($headers as $hline) {
            // search for things like "HTTP/1.1 200 OK" , "HTTP/1.0 200 OK" , "HTTP/1.1 301 PERMANENTLY MOVED" , "HTTP/1.1 400 Not Found" , etc.
            // note that the exact syntax/version/output differs, so there is some string magic involved here
            if (preg_match('/^HTTP\/\S+\s+([1-9][0-9][0-9])\s+.*/', $hline, $matches)) {// "HTTP/*** ### ***"
                $code = $matches[1];
                return $code;
            }
        }
        // no HTTP/xxx found in headers:
        return false;
    }
    // no headers :
    return false;
}
