<?php
// TODO: quiz2 will be for 
    $pageTitle = "AI Assisted Questions";
    $pageDesc = "On the fly generated AI questions";
    $json=""; // Leave blank

    /* The AI Prompt would be:

    I am studying for loops of python.

Please generate as many questions in the form of multiple choices, select all that apply, fill in the blanks, true or false, flash cards, mix and match, and order in the correct sequence. Do not access online. Only generate questions from this lesson.

For multiple choice questions: Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the question. Column 4 will instruct how to answer the question. Column 5 will be filled exact text "Multiple Choice". Column 6 will be the correct answer (Please have 1 for a, 2 for b, 3 for c, and 4 for d). Columns 5 and onwards will be a multiple choice. For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma. Please have no labels a), b), c), etc for multiple choices because they will be in columns anyways.

For select all that apply questions: Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the question. Column 4 will instruct how to answer the question. Column 5 will be filled exact text "SATA". Column 6 will be the correct answer(s) (Please have 1 for a, 2 for b, 3 for c, and 4 for d). For example, if the correct answers are a,b,c, then Column 6 will have "1,2,3". Columns 7 and onwards will have the multiple choices. For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma. Please have no labels a), b), c), etc for multiple choices because they will be in columns anyways.

For fill in the blank questions: Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the question with underlines for blanks. Column 4 will instruct how to answer the question. Column 5 will be filled exact text "Fill in the blank". Column 6 will be filled exact text "1". Column 7 will be the answer. Columns 8, 9, and 10 will be incorrect answers that sound possibly correct to the student. For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma. 

For true or false questions: Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the question. Column 4 will instruct how to answer the question. Column 5 will be filled exact text "True False". Column 6 will be the correct answer (Please have 1 for True, and 2 for False). For example, if the correct answer is true, then Column 6 will have 1. Columns 7 will be filled exact text "True". Column 8 will be filled exact text "False". For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma.

For flash cards: Each flash card has a front side and back side. Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the front side of the card, followed by a line of "====", followed by the back side of the card, all in one cell. Column 4 will be filled exact text "Did you remember correctly?". Column 5 will be filled exact text "Flash card". Column 6 will be filled exact text "1". Column 7 will be filled exact text "Yes". Column 8 will be filled exact text "No". For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma. 

For Order in the Correct Sequence type questions: Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the question and instruction to order the choices into the correct sequence. Column 4 will be filled exact text "Order into the correct sequence". Column 5 will be filled exact text "Ranked". Column 6 will be filled exact text "Na". Columns 7  and onwards will be the choices in their corresponding correct order. For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma. If the choices start with a), b), c), etc, please remove them because they will be in the order of the columns anyways.

For the above mix and match questions: Please convert them into csv format. Column 1 will be filled exact text "". Column 2 will be filled exact text "". Column 3 is the question and instruction to mix and match the choices. Column 4 will be filled exact text "Mix and match". Column 5 will be filled exact text "Mix and match". Column 6 will be filled exact text "Na". Columns 7, 8, 9, and 10 will be the answer keys to the Mix and match. For example, column 7 will be will be multiline in quotes; the first line will be a choice; the second line will be "===="; the third line will be a choice that is the correct match. For each comma separated value, do not surround with quotation marks or double quotation marks unless you need to escape a comma. Do not shuffle the matches. Do not denote the choices with letters or numbers.

I do not care for header row. Once you have the CSV, please convert into a javascript array of arrays. At the first level of the array, those are the CSV lines. The second level of arrays are the values separated by comma.

*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <script>
        // PHP brings in Google Sheet Data directly is faster
        // window.payload = `<?php echo $json; ?>`;
        // window.payload = JSON.parse(window.payload);

        window.payload = [
    ["","","The 'for' loop in Python is typically used with what keyword?","Select the correct answer.","Multiple Choice","1","The 'for' keyword","The 'while' keyword","The 'loop' keyword","The 'iterate' keyword"],
    ["","","Is the 'in' keyword necessary when creating a for loop in Python?","Select the correct answer.","Multiple Choice","4","Absolutely necessary","Not necessary but reduces errors","Only necessary for while loops","Not necessary at all"],
    ["","","For loops in Python cannot be used to iterate over what?","Select the incorrect option.","Multiple Choice","3","Items in a list","The iteration count","Items in a dictionary","All loop types"],
    ["","","Which of the following are common uses for for loops in Python? (Select all that apply)","Select all that apply.","SATA","1,3","Loop through items in a list","Repeat code a certain number of times","Iterate over a range of numbers","Exit the program"],
    ["","","In Python, a for loop automatically loops over the ______.","Fill in the blank.","Fill in the blank","1","range","iteration","list","loop"],
    ["","","A for loop continues until the iterator is exhausted.","Select true or false.","True False","2","True","False"],
    ["","","Using 'break' can immediately terminate a for loop in Python.","Select true or false.","True False","1","True","False"],
    ["","","What keyword is used to initiate a for loop in Python?\n====\nThe 'for' keyword.","Did you remember correctly?","Flash card","1","Yes","No"],
    ["","","What do you need to create before using a for loop?\n====\nAn iterable object or a range of numbers.","Did you remember correctly?","Flash card","1","Yes","No"],
    ["","","Place the steps for writing a for loop in the correct order.","Order into the correct sequence","Ranked","Na","Define or identify the sequence","Write 'for' followed by a variable name","Use 'in' followed by the sequence","Write the code you wish to repeat"],
    ["","","Match the Python loop component with its description.","Mix and match","Mix and match","Na","for\n====\nKeyword to initiate a loop","in\n====\nUsed to specify the sequence","range()\n====\nFunction to create a sequence of numbers","break\n====\nExits the loop before completion"]
];

            
        // window.payload = window.payload.split("");

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
    <link href="assets/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/quiz.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.min.css">

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
        <a target="_blank" href="https://www.linkedin.com/in/weng-fung/" rel="nofollow"><img src="https://img.shields.io/badge/LinkedIn-blue?style=flat&logo=linkedin&labelColor=blue" alt="Linked-In" data-canonical-src="https://img.shields.io/badge/LinkedIn-blue?style=flat&amp;logo=linkedin&amp;labelColor=blue" style="max-width:10ch;"></a>
        <a target="_blank" href="https://www.youtube.com/user/Siphon880yt/" rel="nofollow"><img src="https://img.shields.io/badge/Youtube-red?style=flat&logo=youtube&labelColor=red" alt="Youtube" data-canonical-src="https://img.shields.io/badge/Youtube-red?style=flat&amp;logo=youtube&amp;labelColor=red" style="max-width:10ch;"></a>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery-ui-touch-punch@0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/handlebars@2.0.0/dist/handlebars.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="./assets/formatters/format-question-text.js?v=<?php echo time(); ?>"></script>
    <script src="./assets/formatters/format-choices.js?v=<?php echo time(); ?>"></script>
    <script src="./assets/quiz.js?v=<?php echo time(); ?>"></script>
</body>
</html>