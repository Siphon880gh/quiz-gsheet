<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$_SESSION["spreadsheet-link"] = "https://docs.google.com/spreadsheets/d/1SHqEB2MVho0jP81cT9bDEo5VUZOzkfwNC1BZ3qB8VQE/";
$connectToSpreadSheetUrlId = "1SHqEB2MVho0jP81cT9bDEo5VUZOzkfwNC1BZ3qB8VQE";
$connectToTab = "AbsolutePitch";

$pageTitle = "Quiz: Identify Absolute Pitches";
$pageDesc = "Play a quick quiz game to test your ears' absolute pitch skills.";

// Optionally add timer in seconds to specific quiz:
// $timeLeft = "";
$timeLeft = 40;

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