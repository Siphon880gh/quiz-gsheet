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
        window.payload = JSON.parse(window.payload);
        for(i=0;i<window.payload.length;i++) {
            for(j=0; j<window.payload[i].length; j++) {
                    // At the level of row i -> cell j 
                    window.payload[i][j] = window.payload[i][j].replaceAll("__DOUBLE__QUOTE__", '"');
                    console.log(window.payload[i][j]);
            } // for
        } // for
    </script>

    <!-- Styling  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/quiz.css?v=<?php echo time(); ?>">
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
        $jumbo = "container bg-light rounded-3 px-3 py-3 px-md-5 py-md-4 my-2 my-2 my-md-4 my-md-4";
    ?>
    
    <div class="container-fluid">
        <header class="site-header clearfix">
            <h1 class="site-title display-3-off"><?php echo $pageTitle; ?></h1>
            <nav class="site-nav">
                <i class="nav-mobile-icon fas fa-bars" onclick="event.target.classList.toggle('active')"></i>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION["root_url"]; ?>">
                            <i class="fas fa-arrow-left"></i> More Quizzes
                        </a>
                    </li>
                    <li class="nav-item">
                        |
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="questions.shuffle(); alert('Reshuffled questions');">
                            <i class="fas fa-random"></i> Reshuffle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION["spreadsheet-link"]; ?>" target="_blank">
                            <i class="fas fa-table"></i> Google Sheet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="modal" data-bs-target="#modal-more-questions" style="cursor:pointer;">
                        <i class="fas fa-times-circle"></i> More Questions
                        </a>
                    </li>
                    </li>
                </ul>
            </nav>
        </header>
        <main class="site-body">
            <article class="intro <?php echo $jumbo; ?>" data-page=0>
                <h2 class="intro-title display-5-off">Questions</h2>
                <p class="intro-description lead"><?php echo $pageDesc; ?></p>
                <section class="btn-wrapper text-center my-4">
                    <!--
                    <button class="btn btn-lg btn-primary px-4" onclick="ui.nextPage()">Start Unshuffled</button>
                    <button class="btn btn-lg btn-secondary me-2" onclick="questions.shuffle(); ui.nextPage();">Start Shuuffled</button>
                    -->
                    <button class="btn btn-lg btn-primary me-2" onclick="questions.shuffle(); ui.nextPage();">Start</button>
                </section>
            </article>


            <article class="question <?php echo $jumbo; ?> d-none" data-page=1>
                <!-- #template-question will interpolate and hydrate here -->
            </article>
            

            <article class="finish text-center <?php echo $jumbo; ?> d-none" data-page=2>
                <h2 class="finish-title display-5-off">Finished!</h2>
                <p class="finish-description">Thanks for playing.</p>
                <section class="finish-score fs-4 pt-3">__Finished__Score</section>
                <footer class="pt-3 clearfix">
                    <span class="float-end">
                        <a href="#reload" onclick="window.location.reload()">Play again</a>
                    </span>
                </footer>
            </article>
        </main>
    </div> <!-- Ends container-fluid -->


    <!-- Modal: More Questions -->
    <div class="modal fade" id="modal-more-questions" tabindex="-1" aria-labelledby="modalMoreQuestionsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalMoreQuestionsLabel">Extend Quiz</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="text-center">
                Take all the questions again. Will shuffle:
                <a  role="button" class="nav-link" onclick="questions.doubleQuestions(); alert('Done! You now have the questions twice and shuffled');">x2 All Questions</a>

            </div>
            <div>
                <p></p>
            </div>
            <div class="text-center">
                See more of the current question:
                <a  role="button" class="nav-link" onclick="if(ui.getQuestionIndex()===-1) { alert('Start the quiz first!'); } else { questions.tripleThisQuestion(ui.getQuestionIndex()); alert('Done! You now have this question three more times'); }">x3 This Question</a>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
        </div>
        </div>
    </div>
    </div>

    <div class="container promote-features">
        <hr/>
        By <a href="javascript:void(0)" onclick="$('.creds-socials').toggleClass('d-none');">Weng</a>.<br/>
        <div class='creds-socials d-none'>
        <a target="_blank" href="https://github.com/Siphon880gh" rel="nofollow"><img src="https://img.shields.io/badge/GitHub--blue?style=social&logo=GitHub" alt="Github" data-canonical-src="https://img.shields.io/badge/GitHub--blue?style=social&logo=GitHub" style="max-width:8.5ch;"></a>
        <a target="_blank" href="https://www.linkedin.com/in/weng-fung/" rel="nofollow"><img src="https://camo.githubusercontent.com/0f56393c2fe76a2cd803ead7e5508f916eb5f1e62358226112e98f7e933301d7/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f4c696e6b6564496e2d626c75653f7374796c653d666c6174266c6f676f3d6c696e6b6564696e266c6162656c436f6c6f723d626c7565" alt="Linked-In" data-canonical-src="https://img.shields.io/badge/LinkedIn-blue?style=flat&amp;logo=linkedin&amp;labelColor=blue" style="max-width:10ch;"></a>
        <a target="_blank" href="https://www.youtube.com/user/Siphon880yt/" rel="nofollow"><img src="https://camo.githubusercontent.com/0bf5ba8ac9f286f95b2a2e86aee46371e0ac03d38b64ee2b78b9b1490df38458/68747470733a2f2f696d672e736869656c64732e696f2f62616467652f596f75747562652d7265643f7374796c653d666c6174266c6f676f3d796f7574756265266c6162656c436f6c6f723d726564" alt="Youtube" data-canonical-src="https://img.shields.io/badge/Youtube-red?style=flat&amp;logo=youtube&amp;labelColor=red" style="max-width:10ch;"></a>
        </div>
        Quiz can support multiple choices, select all that apply, fill in the blank, true or false, flash cards, order into correct sequence, and mix and match. It also supports video and audio questions.
    </div>

    <!-- PHP writes to first, then Handlebars takes over -->
    <script id="template-question" type="text/x-handlebars">
        <header class="question-header text-secondary">
            <h2 class="question-title display-5-off">{{{questionTitle}}}</h2>
            <section class="quiz-stats text-end">
                <span id="progress"><span id="progress-index">{{increment questionIndex}}</span>/{{questionsLength}}</span>
                <?php
                
                    if(isset($timeLeft) && gettype($timeLeft)=="integer" && $timeLeft>0) {
                        echo "<span id='time-left'>$timeLeft</span>";
                    }
                ?>
            </section>
        </header>
        <div class="question-description">{{{questionSubtemplate}}}</div>
        <div class="question-instruction">{{{questionInstruction}}}</div>
        <nav class="question-choices-wrapper">
           __inject__choices__subtemplate__
        </nav>
        <section class="question-nav p-2 clearfix">
            {{#if confirmChoiceSubtemplate}}
                {{{confirmChoiceSubtemplate}}}
            {{/if}}
        </section>
    </script>
    
    <!-- Scripts -->
    <script src="//code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0/handlebars.js"></script>
    <script src="//cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/formatters/format-question-text.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/formatters/format-choices.js?v=<?php echo time(); ?>"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/quiz.js?v=<?php echo time(); ?>"></script>
</body>
</html>