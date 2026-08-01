<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$inputs = [
    /* Connections — local CSV in this folder (no Google Sheet API or creds) */
    "spreadsheetLocal"=>"market-types-bridge.csv",

    /* Display */
    "pageTitle"=>"Quiz: Market Types Bridge",
    "pageDescription"=>"Cross-market fundamentals — when to think equities vs futures vs options vs FX vs crypto, and Stock Trainer labeling.",

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
?>
