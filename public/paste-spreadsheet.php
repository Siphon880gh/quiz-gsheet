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
            <p>Paste your quiz spreadsheet as CSV below. Include the full header row and all question rows (same layout as a Google Sheet export). Example format: <code>gsheets/Test/sample-quiz.csv</code>.</p>
<?php if (!empty($pasteFormError)) { ?>
            <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($pasteFormError, ENT_QUOTES, "UTF-8"); ?></div>
<?php } ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="spreadsheetCsv" class="form-label">Quiz CSV data</label>
                    <textarea class="form-control font-monospace" id="spreadsheetCsv" name="spreadsheetCsv" rows="18" placeholder="Paste CSV here..."><?php echo htmlspecialchars($submittedCsv, ENT_QUOTES, "UTF-8"); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Start quiz</button>
            </form>
        </main>
    </div>
</body>
</html>
