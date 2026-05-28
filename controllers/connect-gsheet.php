<?php
// imported $credsGsheetJSONFile from gsheets/nameX/nameY.php

if (!empty($inputs["spreadsheetLocal"])) {
    require_once __DIR__ . "/connect-local-csv.php";
    return;
}

require_once __DIR__ . "/spreadsheet-values.php";

// Setup creds
$client = new \Google_Client();
$client->setApplicationName('Google Sheets API');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig($credsGsheetJSONFile);

// Setup spreadsheet
$service = new \Google_Service_Sheets($client);
$spreadsheetId = $connectToSpreadSheetUrlId;
// From spreadsheet: https://docs.google.com/spreadsheets/d/1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs/
$range = $connectToTab; // here we use the name of the Sheet to get all the rows
$response = $service->spreadsheets_values->get($spreadsheetId, $range);

// OFF|on: Get values tested
$values = $response->getValues();

$json = spreadsheet_values_to_json($values);
// echo $json;
// die();
?>