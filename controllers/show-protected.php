<?php

if(isset($_GET["password"])) {
    $password=$_GET["password"];
    $relativePathing = "..";

    /* SETUP: Setup Google Sheet listing */
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
    {
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
        return $files;
    }

    function is_password_protected($token) {
        // return strpos($token, "gsheets/-")!=false; // Oddly glitches
        $res = strpos($token, "gsheets/-");
        if($res>-1) {
            return true;
        } else {
            return false;
        }
    }
    function is_not_password_protected($token) {
        $res = strpos($token, "gsheets/-");
        if($res>-1) {
            return false;
        } else {
            return true;
        }
    }
    function is_this_password($token) {
        GLOBAL $password;
        $res = strpos($token, "gsheets/-$password");
        if($res>-1) {
            return true;
        } else {
            return false;
        }
    }
    
    $gsheetPaths = glob_recursive("$relativePathing/gsheets/*\.php");
    $gsheetPaths = array_values(array_filter($gsheetPaths, 'is_this_password'));
    // var_dump($gsheetPaths);

    $gsheetNames = [];
    for($i=0; $i<count($gsheetPaths); $i++) {
        $gsheetPath = $gsheetPaths[$i];
        $j = strpos($gsheetPath, "gsheets/");
        if($j!=false) {
            $substringed = substr($gsheetPath, $j+strlen("gsheets/")); // substringed away ./gsheets/ or ../gsheets/
            $substringed = substr($substringed,0, strlen($substringed)-strlen(".php"));
            array_push($gsheetNames, $substringed); // $gsheetName is "<folder>/<filename>"
        }
    } // for

    // die(var_dump($gsheetNames));
    http_response_code(200);
    echo json_encode($gsheetNames);


} else {
    http_response_code(400);
    echo json_encode(["error"=>"Password missing"]);
}


?>