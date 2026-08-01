<?php
session_start();

/* INPUTS
Will be processed into $json and $overrideStyleBlock for templates
______________________________________________________________________ */
$inputs = [
    /* Connections — local CSV in this folder (no Google Sheet API or creds) */
    "spreadsheetLocal"=>"ekg-rhythm-identification.csv",

    /* Display */
    "pageTitle"=>"Quiz: EKG Rhythm Identification",
    "pageDescription"=>"<p>Identify EKG rhythms from local strips (sinus, atrial, junctional, heart block, pacemaker). Not split by rate category — identify the tracing.</p><p>Browse the local strip library: <a href=\"../../hosted-strips/index.html\" target=\"_blank\" rel=\"noopener\">hosted-strips</a>.</p>",

    /* Optionals OR set as defaults 0 and "" respectively */
    "timeLeft"=>0,
    "cssOverride"=>".question {
        border: 1px solid black;
        background-color: white !important;
    }
    .picture-type {
        width: 100% !important;
        max-width: 920px;
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
