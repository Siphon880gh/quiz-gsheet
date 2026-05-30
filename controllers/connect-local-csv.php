<?php

require_once __DIR__ . "/spreadsheet-values.php";

$csvPath = $inputs["spreadsheetLocal"];
if (!preg_match('#^/#', $csvPath)) {
    $csvPath = dirname($_SERVER["SCRIPT_FILENAME"]) . "/" . $csvPath;
}

if (!file_exists($csvPath)) {
    die_quiz_source_error("local CSV file", "file not found: $csvPath");
}

$values = spreadsheet_csv_rows_from_path($csvPath);
$validationError = validate_quiz_spreadsheet_values($values);
if ($validationError !== null) {
    die_quiz_source_error("local CSV file", $validationError);
}

$json = spreadsheet_values_to_json($values);
