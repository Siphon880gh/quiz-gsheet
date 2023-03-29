<?php

// Uninitialized session pathing.
// Force initialization flow
if(!isset($_SESSION["root_url"]) || !isset($_SESSION["root_dir"])) {

    function getBaseGsheetUrl() {

        function getHttpHttps() {
            $isHttps =
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443;
            if($isHttps)
            return "https://";
            return "http://";
        }
        
        $requestUrIEncoded = getHttpHttps() . ($_SERVER["HTTP_HOST"]) . $_SERVER['REQUEST_URI']; // url is encoded

        // Wrangle away URL queries
        $string = $requestUrIEncoded;
        $pattern = '/(.*)\?+.*/i';
        $replacement = '${1}';
        $requestUrIEncoded= preg_replace($pattern, $replacement, $string);

        // Remove PHP filename
        $filename = basename(__FILE__); // url unencoded
        $filename = rawurlencode($filename); // url encoded
        $requestUriEncodedWithoutFilename = str_replace($filename, "", $requestUrIEncoded); // $requestUrIEncoded is subject

        // Cd up to app root
        $string = $requestUriEncodedWithoutFilename;
        $pattern = '/(.*\/)gsheets\/.*/i';
        $replacement = '${1}';
        $requestUriRootPath = preg_replace($pattern, $replacement, $string);

        return $requestUriRootPath;
    } // getBaseGsheetUrl

    $baseGsheetUrl = getBaseGsheetUrl();
    $callbackHereUrl = getHttpHttps() . ($_SERVER["HTTP_HOST"]) . $_SERVER['REQUEST_URI']; // url is encoded

    header("Location: $baseGsheetUrl?callback=$callbackHereUrl");
} // if

?>