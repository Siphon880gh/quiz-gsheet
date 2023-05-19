<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$inputs = [
    /* Connections */
    "spreadsheetUrl"=>"https://docs.google.com/spreadsheets/d/1ADoGCwc-u2DpuEHx_UurLjxV_d8cSvaGHoGdCD-iI5I/",
    "spreadsheetId"=>"1ADoGCwc-u2DpuEHx_UurLjxV_d8cSvaGHoGdCD-iI5I",
    "tabName"=>"Bed Management",

    /* Display */
    "pageTitle"=>"Quiz: Bed Management Questions",
    "pageDescription"=>"Questions about managing patient beds in the inpatient hospital These are more practical and what actually happens rather than textbook questions.
    
    <br/><br/>
    <div style='width:290px; margin:0 auto; text-align:center; line-height:1.65em;'>
        <b style='font-size:120%'>Coming soon</b>
        <ul style='display:flex; flex-flow:column nowrap; align-items:center;'>
            <li>Patient bathing</li>
            <li>Patient proning</li>
            <li>Patient turning</li>
            <li>Bed to sitting</li>
            <li>Bathing in bed</li>
            <li>Suppository position, Foley position, etc</li>
            <li>Setting up new bed (linens, etc)</li>
        </ul>
    </div>
    ",

    /* Optionals OR set as defaults 0 and "" respectively */
    "timeLeft"=>0,
    "cssOverride"=>".question {
        border: 1px solid black;
        background-color: white !important;
    }"
];

/* DEVELOPER READABILITY
This is for readability
______________________________________________________________________ */
$_SESSION["spreadsheet-link"] = $inputs["spreadsheetUrl"];
$connectToSpreadSheetUrlId = $inputs["spreadsheetId"];
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

// Check credential file correct. HINT: Named the same as PHP script and ends with ".creds.json"
$credsGsheetJSONFile = rawurldecode(basename(__FILE__, '.php') . ".creds.json");
file_exists($credsGsheetJSONFile) or die("Error: Failed to load credentials $credsGsheetJSONFile. Contact administrator");

// Load in Composer libraries
require_once $_SESSION["root_dir"] . '/vendor/autoload.php';

// Connect API with credentials
require_once "../../controllers/connect-gsheet.php";

// Render quiz page
require_once "../../controllers/render-quiz.php";
?>