<?php

function spreadsheet_values_to_json($values) {
    for ($i = 0; $i < count($values); $i++) {
        $values[$i] = preg_replace("/\n/", "\\n", $values[$i]);
        $values[$i] = preg_replace("/\"/", "__DOUBLE__QUOTE__", $values[$i]);
    }
    return json_encode($values);
}
