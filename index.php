<?php


session_start();

function isSecureHttps() {
    return
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443;
}


$_SESSION["root_dir"] = __DIR__;
$_SESSION["root_url"] = isSecureHttps()?"https://":"http://" . ($_SERVER["HTTP_HOST"]) . $_SERVER['REQUEST_URI'];
header("Location: gsheets/music-sight-reading/notes-natural.php");
exit();
?>