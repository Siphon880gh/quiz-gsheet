<?php
/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$connectToSpreadSheetUrlId = "1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs";
$connectToTab = "Sample-AbsolutePitch";
$_SESSION["spreadsheet-link"] = "https://docs.google.com/spreadsheets/d/1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs/";

$pageTitle = "Quiz: Identify Absolute Pitches";
$pageDesc = "Play a quick quiz game to test your ears' absolute pitch skills.";

// Default
// $overrideCSS = "";
// If overriding, type in the inner content of the new style block.
// May want to use !important; flags because Bootstrap has them.
$overrideCSS = "
.question {
    border: 1px solid black;
    background-color: white !important;
}
";

/* ENGINE
   Do not touch
______________________________________________________________________ */

session_start();

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