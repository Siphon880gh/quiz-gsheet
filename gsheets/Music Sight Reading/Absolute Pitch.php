<?php
session_start();

// Check is initialized and not visited directly. If visited directly with no session, then initialize
require_once "../../controllers/check-initialized.php";

// Check credential file correct. HINT: Named the same as PHP script and ends with ".creds.json"
file_exists("fake.json") or die("Error: Failed to load credentials. Contact administrator");

// Load in Composer libraries
require_once $_SESSION["root_dir"] . '/vendor/autoload.php';

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

// Setup creds
$client = new \Google_Client();
$client->setApplicationName('Google Sheets API');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$filename = basename(__FILE__, '.php');
$path = "$filename.creds.json";
$client->setAuthConfig($path);

// Setup spreadsheet
$service = new \Google_Service_Sheets($client);
$spreadsheetId = $connectToSpreadSheetUrlId;
// From spreadsheet: https://docs.google.com/spreadsheets/d/1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs/
$range = $connectToTab; // here we use the name of the Sheet to get all the rows
$response = $service->spreadsheets_values->get($spreadsheetId, $range);

// OFF|on: Get values tested
$values = $response->getValues();
// var_dump($values);

// Setup render
$json = json_encode($values);
$json = str_replace("`","\\`", $json); // escape backticks

// Render quiz page
require_once $_SESSION["root_dir"] . "/public/quiz.php"
?>