<?php
// imported $credsGsheetJSONFile from gsheets/nameX/nameY.php

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
// var_dump($values);

// Setup render
$json = json_encode($values);
$json = str_replace("`","\\`", $json); // escape backticks
?>