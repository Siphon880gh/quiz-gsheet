<?php

require_once __DIR__ . "/check-initialized.php";

$useOfflineSource = !empty($inputs["spreadsheetLocal"])
    || !empty($inputs["spreadsheetUserProvides"]);

if (!$useOfflineSource) {
    $credsGsheetJSONFile = $inputs["creds"];
    file_exists($credsGsheetJSONFile) or die("Error: Failed to load credentials $credsGsheetJSONFile. Contact administrator");
    require_once $_SESSION["root_dir"] . "/vendor/autoload.php";
}

require_once __DIR__ . "/connect-gsheet.php";
require_once __DIR__ . "/render-quiz.php";
