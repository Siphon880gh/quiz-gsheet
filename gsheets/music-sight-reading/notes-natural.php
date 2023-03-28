<?php
session_start();
// ini_set('display_errors',1); 
// error_reporting(E_ALL); 

require_once $_SESSION["root_dir"] . '/vendor/autoload.php';


/* INPUTS
    Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$spreadSheetUrlId = "1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs";
$tabName = "Sample";

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


// Current Developer flow:
// window.payload = `<_php require_once "./gsheet-connect.php"; _>`;
// Keep in mind all backticks from Google Sheet are escaped into \`

// TODO: Make more modular with different quizes / Google sheets 
// Which means different pairs of Sheet ID and Sheet Name
// The Sheet ID that's conventionally in their document is actually associated with the Google Sheet Workbook. 
// So you still need the sheet name (aka tab name). 

// Setup creds
$client = new \Google_Client();
$client->setApplicationName('Google Sheets API');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$path = 'notes-natural.creds.json';
$client->setAuthConfig($path);

// Setup spreadsheet
$service = new \Google_Service_Sheets($client);
$spreadsheetId = $spreadSheetUrlId;
// From spreadsheet: https://docs.google.com/spreadsheets/d/1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs/
$range = $tabName; // here we use the name of the Sheet to get all the rows
$response = $service->spreadsheets_values->get($spreadsheetId, $range);

// OFF|on: Get values tested
$values = $response->getValues();
// var_dump($values);

// Setup render
$json = json_encode($values);
$json = str_replace("`","\\`", $json); // escape backticks

require_once $_SESSION["root_dir"] . "/public/page.php"
?>