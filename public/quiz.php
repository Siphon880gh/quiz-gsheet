<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script>
        // PHP brings in Google Sheet Data directly is faster
        window.payload = `<?php echo $json; ?>`;
        window.payload = JSON.parse(window.payload)
    </script>

    <!-- Styling  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/quiz.css">

<?php
if(isset($overrideCSS) && strlen($overrideCSS)>0) {
echo "<style>
$overrideCSS
</style>";
}
?>

</head>

<body>
    <?php
        // Bootstrap annoyingly removed Jumbotron, so to improve readability:
        $jumbo = "container bg-light px-5 py-4 rounded-3 my-4";
    ?>
    
    <div class="container-fluid">
        <header class="site-header clearfix">
            <h1 class="site-title display-3 float-start"><?php echo $pageTitle; ?></h1>
            <nav class="site-nav float-end">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#shuffled" onclick="questions.shuffle()">
                            Shuffle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION["spreadsheet-link"]; ?>" target="_blank">
                            Admin Edit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION["root_url"]; ?>">
                            Home
                        </a>
                    </li>
                </ul>
            </nav>
        </header>
        <main class="site-body">
            <article class="intro <?php echo $jumbo; ?>" data-page=0>
                <h2 class="intro-title display-5">Questions</h2>
                <p class="intro-description lead"><?php echo $pageDesc; ?></p>
                <section class="btn-wrapper text-center my-4">
                    <button class="btn btn-lg btn-primary" onclick="ui.nextPage()">Start</button>
                </section>
            </article>


            <article class="question position-relative <?php echo $jumbo; ?> d-none" data-page=1>
                <!-- #template-question will interpolate and hydrate here -->
            </article>
            

            <article class="finish <?php echo $jumbo; ?> d-none" data-page=2>
                <h2 class="finish-title display-5">Finished!</h2>
                <p class="finish-description">Thanks for playing.</p>
                <section class="finish-score fs-4">F score</section>
                <footer class="pt-3 clearfix">
                    <span class="float-end">
                        <a href="#reload" onclick="window.location.reload()">Play again</a>
                    </span>
                </footer>
            </article>
        </main>
    </div> <!-- Ends container-fluid -->

    <script id="template-question" type="text/x-handlebars">
        <aside class="question-step text-secondary p-2 position-absolute top-0 end-0">
            <span>Progress: {{increment questionIndex}}/{{questionsLength}}</span>
        </aside>
        <h2 class="question-title display-5">{{questionTitle}}</h2>
        <div class="question-description">{{{questionText}}}</div>
        <nav class="question-choices-wrapper">
            <ul class="question-choices" data-question-index="{{questionIndex}}" disabled>
                <li class="question-choice" data-choice="1">{{choice1}}</li>
                <li class="question-choice" data-choice="2">{{choice2}}</li>
                <li class="question-choice" data-choice="3">{{choice3}}</li>
                <li class="question-choice" data-choice="4">{{choice4}}</li>
            </ul>
        </nav>
        <!-- Experimenting A/B split design -->
        <!-- <section class="result p-2 clearfix">
            <div class="span float-start"></div>
            <div class="span float-end">
                <button class="btn btn-primary btn-sm">Next</button>
            </div>
        </section> -->
    </script>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0/handlebars.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/quiz.js"></script>
</body>
</html>