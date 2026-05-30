<?php

require_once __DIR__ . "/spreadsheet-values.php";

$pasteFormError = "";
$submittedCsv = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["spreadsheetCsv"])) {
    $submittedCsv = $_POST["spreadsheetCsv"];
    $trimmedCsv = trim($submittedCsv);

    if ($trimmedCsv === "") {
        $pasteFormError = "Paste question CSV in the quiz format (including the header row), then submit.";
    } else {
        $values = spreadsheet_csv_rows_from_string($trimmedCsv);
        $validationError = validate_quiz_spreadsheet_values($values);
        if ($validationError !== null) {
            $pasteFormError = "Could not use this CSV ($validationError). " . quiz_source_format_hint();
        } else {
            $json = spreadsheet_values_to_json($values);
            return;
        }
    }
}

$sampleCsvPath = $_SESSION["root_dir"] . "/gsheets/Test/sample-quiz.csv";
$sampleCsv = is_readable($sampleCsvPath) ? file_get_contents($sampleCsvPath) : "";

require_once $_SESSION["root_dir"] . "/public/paste-spreadsheet.php";
exit();
