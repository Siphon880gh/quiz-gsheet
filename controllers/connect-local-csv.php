<?php

require_once __DIR__ . "/spreadsheet-values.php";

$csvPath = $inputs["spreadsheetLocal"];
if (!preg_match('#^/#', $csvPath)) {
    $csvPath = dirname($_SERVER["SCRIPT_FILENAME"]) . "/" . $csvPath;
}

file_exists($csvPath) or die("Error: Failed to load spreadsheet $csvPath. Contact administrator");

$values = [];
if (($handle = fopen($csvPath, "r")) !== false) {
    while (($row = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
        $values[] = $row;
    }
    fclose($handle);
}

$json = spreadsheet_values_to_json($values);
