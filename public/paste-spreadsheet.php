<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, "UTF-8"); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/quiz.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.min.css">
<?php
if (isset($overrideCSS) && strlen($overrideCSS) > 0) {
    echo "<style>\n$overrideCSS\n</style>";
}
?>
</head>
<body>
    <div class="container-fluid">
        <header class="site-header clearfix my-3">
            <h1 class="site-title display-5"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, "UTF-8"); ?></h1>
            <nav class="site-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION["root_url"]; ?>">
                            <i class="fas fa-arrow-left"></i> More Quizzes
                        </a>
                    </li>
                </ul>
            </nav>
        </header>
        <main class="container bg-light rounded-3 px-3 py-4 px-md-5 my-4">
            <p class="lead"><?php echo htmlspecialchars($pageDesc, ENT_QUOTES, "UTF-8"); ?></p>
            <p>This quiz reads a specific CSV layout and turns each row into an interactive question—not a generic spreadsheet export. You may already have question CSV from <a href="https://codernotes.wengindustries.com" target="_blank" rel="noopener">Weng's notebooks</a> (e.g. codernotes.wengindustries.com). Paste it below with the header row and all question rows.</p>
<?php if (!empty($sampleCsv)) { ?>
            <div class="mb-3">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sampleCsvToggle" aria-expanded="false" aria-controls="sampleCsvPanel">
                        <span class="sample-csv-chevron" aria-hidden="true">&#9656;</span> See example CSV format
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" id="transferSampleCsv">Transfer</button>
                </div>
                <div id="sampleCsvPanel" class="sample-csv-panel mt-2 d-none">
                    <pre class="sample-csv-preview bg-white border rounded p-3 font-monospace small mb-0" style="max-height: 20rem; overflow: auto; white-space: pre-wrap;"><code id="sampleCsvContent"><?php echo htmlspecialchars($sampleCsv, ENT_QUOTES, "UTF-8"); ?></code></pre>
                    <textarea id="sampleCsvSource" class="visually-hidden" readonly aria-hidden="true"><?php echo htmlspecialchars($sampleCsv, ENT_QUOTES, "UTF-8"); ?></textarea>
                </div>
            </div>
<?php } ?>
<?php if (!empty($pasteFormError)) { ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($pasteFormError, ENT_QUOTES, "UTF-8"); ?></div>
<?php } ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="spreadsheetCsv" class="form-label">Question CSV</label>
                    <textarea class="form-control font-monospace" id="spreadsheetCsv" name="spreadsheetCsv" rows="18" placeholder="Paste question CSV here (include the header row)..."><?php echo htmlspecialchars($submittedCsv, ENT_QUOTES, "UTF-8"); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Start quiz</button>
            </form>
        </main>
    </div>
<?php if (!empty($sampleCsv)) { ?>
    <script>
    (function () {
        var toggle = document.getElementById("sampleCsvToggle");
        var panel = document.getElementById("sampleCsvPanel");
        var chevron = toggle.querySelector(".sample-csv-chevron");
        var transferBtn = document.getElementById("transferSampleCsv");
        var field = document.getElementById("spreadsheetCsv");
        var source = document.getElementById("sampleCsvSource");

        toggle.addEventListener("click", function () {
            var expanded = panel.classList.toggle("d-none") === false;
            toggle.setAttribute("aria-expanded", expanded ? "true" : "false");
            chevron.innerHTML = expanded ? "&#9662;" : "&#9656;";
        });

        transferBtn.addEventListener("click", function () {
            field.value = source.value;
            field.focus();
            var label = transferBtn.textContent;
            transferBtn.textContent = "Transferred!";
            window.setTimeout(function () {
                transferBtn.textContent = label;
            }, 2000);
        });
    })();
    </script>
<?php } ?>
</body>
</html>
