<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$_SESSION["spreadsheet-link"] = "https://docs.google.com/spreadsheets/d/1ADoGCwc-u2DpuEHx_UurLjxV_d8cSvaGHoGdCD-iI5I/";
$connectToSpreadSheetUrlId = "1ADoGCwc-u2DpuEHx_UurLjxV_d8cSvaGHoGdCD-iI5I";
$connectToTab = "Skills";

$pageTitle = "Quiz: Test ICU Skills";
$pageDesc = "See if you know your Foley, NGT, IV Insertion, Aline, etc skills.";

// Optionally add timer in seconds to specific quiz:
// $timeLeft = "";
$timeLeft = 120;

// Add CSS to specific quiz:
// If overriding, type in the inner content of the new style block.
// May want to use !important; flags because Bootstrap has them.
// $overrideCSS = "";
$overrideCSS = "
.question {
    border: 1px solid black;
    background-color: white !important;
}
";

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