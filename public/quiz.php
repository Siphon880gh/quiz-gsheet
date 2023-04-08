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
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">

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
        $jumbo = "container bg-light rounded-3 px-1 py-2 px-md-5 py-md-4 my-2 my-2 my-md-4 my-md-4";
    ?>
    
    <div class="container-fluid">
        <header class="site-header clearfix">
            <h1 class="site-title display-3-off float-start"><?php echo $pageTitle; ?></h1>
            <nav class="site-nav float-end">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="questions.shuffle()">
                            Shuffle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION["spreadsheet-link"]; ?>" target="_blank">
                            Google Sheet
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
                <h2 class="intro-title display-5-off">Questions</h2>
                <p class="intro-description lead"><?php echo $pageDesc; ?></p>
                <section class="btn-wrapper text-center my-4">
                    <button class="btn btn-lg btn-secondary me-2" onclick="questions.shuffle(); ui.nextPage();">Shuffle</button>
                    <button class="btn btn-lg btn-primary px-4" onclick="ui.nextPage()">Start</button>
                </section>
            </article>


            <article class="question <?php echo $jumbo; ?> d-none" data-page=1>
                <!-- #template-question will interpolate and hydrate here -->
            </article>
            

            <article class="finish <?php echo $jumbo; ?> d-none" data-page=2>
                <h2 class="finish-title display-5-off">Finished!</h2>
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

    <!-- PHP writes to first, then Handlebars takes over -->
    <script id="template-question" type="text/x-handlebars">
        <header class="question-header text-secondary">
            <h2 class="question-title display-5-off">{{questionTitle}}</h2>
            <section class="question-quiz text-end">
                <span id="progress">{{increment questionIndex}}/{{questionsLength}}</span>
                <?php
                
                    if(isset($timeLeft) && gettype($timeLeft)=="integer" && $timeLeft>0) {
                        echo "<span id='time-left'>$timeLeft</span>";
                    }
                ?>
            </section>
        </header>
        <div class="question-description">{{{questionText}}}</div>
        <div class="question-instruction">{{{questionInstruction}}}</div>
        <nav class="question-choices-wrapper">
            <ul class="question-choices" data-question-index="{{questionIndex}}" disabled>
                {{#each choices}}
                <li class="question-choice" data-choice-index="{{increment this.index}}">{{this.text}}</li>
                {{/each}}
            </ul>
        </nav>
        {{#if __isSata}}
        <section class="result p-2 clearfix">
            <div class="span float-end">
                <button class="btn btn-primary btn-sm" onclick="if(document.querySelector('.chosen')) ui.pressedSATADone()">Selected all that apply</button>
            </div>
        </section>
        {{/if}}
    </script>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0/handlebars.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/formatters/format-question-text.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/formatters/format-choices.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/quiz.js"></script>
</body>
</html>