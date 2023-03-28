<?php
session_start();

if(!isset($_SESSION["root_url"]) || !isset($_SESSION["root_dir"])) {
    die('Please visit the <a href="https://www.wengindustry.com/tools/quiz-gsheet/">main quiz page</a>.');
    // TODO: This is a problem of initializing the session variables. If the user never visited the main page,
    //       then we don't have the session variables we need. We can iframe into the main page then meta
    //       refresh to initialize the session variables. Or can redirect to the main page with a URL query 
    //       for what to click to come back here. For now we just force the user to visit the main page.
}

require_once $_SESSION["root_dir"] . '/vendor/autoload.php';


/* INPUTS
    Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$connectToSpreadSheetUrlId = "1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs";
$connectToTab = "Sample-Natural Notes";

$pageTitle = "Quiz: Music Natural Notes";
$pageDesc = "Play a quick quiz game to test your sight reading skills in music.";

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