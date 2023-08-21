<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$inputs = [
    /* Connections */
    "spreadsheetUrl"=>"https://docs.google.com/spreadsheets/d/1ADoGCwc-u2DpuEHx_UurLjxV_d8cSvaGHoGdCD-iI5I/",
    "tabName"=>"Registry Travel",
    "creds"=>dirname(__FILE__) . "/../../../../../keys/quizer-temporal-fx-381723.json",

    /* Display */
    "pageTitle"=>"Quiz: Registry Travel",
    "pageDescription"=>"These are common policies and practices between hospitals. Go into any hospital as a travel nurse and quickly adapt.",

    /* Optionals OR set as defaults 0 and "" respectively */
    "timeLeft"=>0,
    "cssOverride"=>".question {
        border: 1px solid black;
        background-color: white !important;
    }"
];

/* DEVELOPER READABILITY & MAINTAINABILITY
This is for readability & maintainability
______________________________________________________________________ */
$_SESSION["spreadsheet-link"] = $inputs["spreadsheetUrl"];

$re = '/.*\/(.+)\/$/m';
preg_match_all($re, $inputs["spreadsheetUrl"], $matches, PREG_SET_ORDER, 0);
$connectToSpreadSheetUrlId = $matches[0][1];
$connectToTab = $inputs["tabName"];

$pageTitle = $inputs["pageTitle"];
$pageDesc = $inputs["pageDescription"];

$timeLeft = $inputs["timeLeft"];
$overrideCSS = $inputs["cssOverride"];

/* ENGINE
   Do not touch
______________________________________________________________________ */

// Check is initialized and not visited directly. If visited directly with no session, then initialize
// Error? gsheets accept only flat directory listing. It would have all folders then inside folder would have the quiz php files and credential creds.json files.
require_once "../../controllers/check-initialized.php";

// Check credential file correct.
$credsGsheetJSONFile = $inputs["creds"];
file_exists($credsGsheetJSONFile) or die("Error: Failed to load credentials $credsGsheetJSONFile. Contact administrator");

// Load in Composer libraries
require_once $_SESSION["root_dir"] . '/vendor/autoload.php';

// Connect API with credentials
require_once "../../controllers/connect-gsheet.php";

// Render quiz page
require_once "../../controllers/render-quiz.php";
?>