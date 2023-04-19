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

// Make parseable
for($i = 0; $i<count($values); $i++) {
    // Otherwise bad control character in string literal in JSON:
    $values[$i] = preg_replace("/\n/", "\\n", $values[$i]);
    // Otherwise double quote breaks JSON. Will convert back on Javascript side.
    $values[$i] = preg_replace("/\"/", "__DOUBLE__QUOTE__", $values[$i]);
}

// In the future might consider flag: JSON_UNESCAPED_SLASHES
$json = json_encode($values);
// echo $json;
// die();
?>