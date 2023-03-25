<?php
require_once __DIR__ . '/vendor/autoload.php';

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
$path = 'keys/credentials.json';
$client->setAuthConfig($path);

// Setup spreadsheet
$service = new \Google_Service_Sheets($client);
$spreadsheetId = '1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs';
// From spreadsheet: https://docs.google.com/spreadsheets/d/1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs/
$range = 'Active'; // here we use the name of the Sheet to get all the rows
$response = $service->spreadsheets_values->get($spreadsheetId, $range);

// OFF|on: Get values tested
$values = $response->getValues();
// var_dump($values);

// Setup render
$json = json_encode($values);
$jsonEscapedBackticks = str_replace("`","\\`", $json);
echo $jsonEscapedBackticks

?>