<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$inputs = [
    /* Connections — user pastes CSV in the browser (no Google Sheet API or creds) */
    "spreadsheetUserProvides"=>1,

    /* Display */
    "pageTitle"=>"Quiz: All Question Types (Paste CSV)",
    "pageDescription"=>"Paste CSV exported from a spreadsheet (include the header row). Try copying gsheets/Test/sample-quiz.csv.",

    /* Optionals OR set as defaults 0 and "" respectively */
    "timeLeft"=>0,
    "cssOverride"=>".question {
        border: 1px solid black;
        background-color: white !important;
    }"
];

/* DEVELOPER READABILITY & MAINTAINABILITY
This is for readability & maintainability
______________________________________________________________________ */
$_SESSION["spreadsheet-link"] = "";

$pageTitle = $inputs["pageTitle"];
$pageDesc = $inputs["pageDescription"];

$timeLeft = $inputs["timeLeft"];
$overrideCSS = $inputs["cssOverride"];

/* ENGINE
   Do not touch
______________________________________________________________________ */
require_once "../../controllers/quiz-engine.php";
