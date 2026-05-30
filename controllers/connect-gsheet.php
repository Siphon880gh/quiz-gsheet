<?php
// imported $credsGsheetJSONFile from gsheets/nameX/nameY.php

if (!empty($inputs["spreadsheetUserProvides"])) {
    require_once __DIR__ . "/connect-user-csv.php";
    return;
}

if (!empty($inputs["spreadsheetLocal"])) {
    require_once __DIR__ . "/connect-local-csv.php";
    return;
}

require_once __DIR__ . "/spreadsheet-values.php";

$spreadsheetId = $connectToSpreadSheetUrlId ?? "";
if ($spreadsheetId === "" && !empty($inputs["spreadsheetUrl"])) {
    $re = '/.*\/(.+)\/?$/m';
    if (preg_match($re, $inputs["spreadsheetUrl"], $matches)) {
        $spreadsheetId = $matches[1];
    }
}
if ($spreadsheetId === "") {
    die_quiz_source_error("Google Sheet URL", "could not parse spreadsheet ID from spreadsheetUrl");
}

$tabName = $connectToTab ?? ($inputs["tabName"] ?? "");
if ($tabName === "") {
    die_quiz_source_error("Google Sheet tab", "tabName is missing or empty");
}

// Setup creds
$client = new \Google_Client();
$client->setApplicationName("Google Sheets API");
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType("offline");
$client->setAuthConfig($credsGsheetJSONFile);

// Setup spreadsheet
$service = new \Google_Service_Sheets($client);
$range = $tabName;

try {
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
} catch (\Exception $e) {
    die_quiz_source_error("Google Sheet API", $e->getMessage());
}

$values = $response->getValues();
if (empty($values)) {
    die_quiz_source_error("Google Sheet tab", "no rows returned for tab \"$tabName\"");
}

$validationError = validate_quiz_spreadsheet_values($values);
if ($validationError !== null) {
    die_quiz_source_error("Google Sheet tab", $validationError);
}

$json = spreadsheet_values_to_json($values);
