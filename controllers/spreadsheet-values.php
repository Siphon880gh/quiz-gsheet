<?php

function quiz_source_format_hint() {
    return "For developers: confirm the spreadsheet/CSV uses the quiz column layout (A numbering, B title, C question, D instruction, E question type, F correct choice, then choice columns). Header labels can be named anything. See gsheets/Test/sample-quiz.csv.";
}

/** Minimum columns through F (correct choice); matches public/assets/quiz.js atColumn. */
function quiz_spreadsheet_min_column_count() {
    return 6;
}

function quiz_spreadsheet_max_row_width($values, $rowLimit = 5) {
    $maxWidth = 0;
    $limit = min($rowLimit, count($values));
    for ($i = 0; $i < $limit; $i++) {
        $maxWidth = max($maxWidth, count($values[$i]));
    }
    return $maxWidth;
}

function die_quiz_source_error($sourceLabel, $detail) {
    $hint = quiz_source_format_hint();
    die("Quiz source could not be loaded ($sourceLabel: $detail). $hint");
}

function spreadsheet_csv_rows_from_stream($handle) {
    $values = [];
    while (($row = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
        $values[] = $row;
    }
    return $values;
}

function spreadsheet_csv_rows_from_string($csv) {
    $handle = fopen("php://memory", "r+");
    fwrite($handle, $csv);
    rewind($handle);
    $values = spreadsheet_csv_rows_from_stream($handle);
    fclose($handle);
    return $values;
}

function spreadsheet_csv_rows_from_path($path) {
    $handle = fopen($path, "r");
    if ($handle === false) {
        return [];
    }
    $values = spreadsheet_csv_rows_from_stream($handle);
    fclose($handle);
    return $values;
}

function validate_quiz_spreadsheet_values($values) {
    if (empty($values) || count($values) < 2) {
        return "need a header row and at least one question row";
    }
    $minColumns = quiz_spreadsheet_min_column_count();
    if (quiz_spreadsheet_max_row_width($values) < $minColumns) {
        return "need at least $minColumns columns from the left (through correct choice, column F)";
    }
    return null;
}

function spreadsheet_normalize_row_cells($row) {
    $normalized = [];
    foreach ($row as $cell) {
        $cell = (string) $cell;
        $cell = str_replace(["\0", "\r\n", "\r"], ["", "\n", "\n"], $cell);
        $cell = preg_replace("/\"/", "__DOUBLE__QUOTE__", $cell);
        // \x00 cannot appear in a PCRE pattern; strip other C0 controls without it in the class.
        $cell = preg_replace("/[\x01-\x08\x0B\x0C\x0E-\x1F]/", "", $cell);
        $normalized[] = $cell;
    }
    return $normalized;
}

function spreadsheet_values_to_json($values) {
    for ($i = 0; $i < count($values); $i++) {
        $values[$i] = spreadsheet_normalize_row_cells($values[$i]);
    }
    $json = json_encode(
        $values,
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );
    if ($json === false) {
        die_quiz_source_error("spreadsheet data", "failed to encode quiz rows as JSON");
    }
    return $json;
}
