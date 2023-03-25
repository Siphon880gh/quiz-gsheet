<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Quiz</title>
    <script>
        window.payload = `<?php require_once "./gsheet-connect.php"; ?>`;
        window.payload = JSON.parse(window.payload)
    </script>


    <!-- Styling  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>
    <?php
        // Bootstrap annoyingly removed Jumbotron, so to improve readability:
        $jumbo = "container bg-light px-5 py-4 rounded-3 my-4";
    ?>
    
    <div class="container-fluid">
        <header class="site-header clearfix">
            <h1 class="site-title display-3 float-start">Music Quiz</h1>
            <nav class="site-nav float-end">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#shuffled" onclick="questions.shuffle()">
                            Shuffle
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://docs.google.com/spreadsheets/d/1ArIhTwTrEACKEvYDsvw4cONX9-LbeH2_FLh1kcfUsQs/" target="_blank">
                            Admin Edit
                        </a>
                    </li>
                </ul>
            </nav>
        </header>
        <main class="site-body">
            <article class="intro <?php echo $jumbo; ?>" data-page=0>
                <h2 class="intro-title display-5">Questions</h2>
                <p class="intro-description lead">Play a quick quiz game to test your sight reading skills in music.</p>
                <section class="btn-wrapper text-center my-4">
                    <button class="btn btn-lg btn-primary" onclick="ui.nextPage()">Start</button>
                </section>
            </article>


            <article class="question position-relative <?php echo $jumbo; ?> d-none" data-page=1>
                <!-- #template-question will interpolate and hydrate here -->
            </article>
            

            <article class="intro <?php echo $jumbo; ?> d-none" data-page=2>
                <h2 class="finish-title display-5">Finished!</h2>
                <p class="finish-description">Thanks for playing.</p>
                <section class="finish-score">F score</section>
            </article>
        </main>
    </div> <!-- Ends container-fluid -->

    <script id="template-question" type="text/x-handlebars">
        <aside class="question-step text-secondary p-2 position-absolute top-0 end-0">
            <span>Progress: {{increment questionIndex}}/{{questionsLength}}</span>
        </aside>
        <h2 class="question-title display-5">{{title}}</h2>
        <p class="question-description">{{{problem}}}</p>
        <nav class="question-choices-wrapper">
            <p>Select correct answer:</p>
            <ul class="question-chioces" data-question-index="{{questionIndex}}">
                <li class="question-choice">{{choice1}}</li>
                <li class="question-choice">{{choice2}}</li>
                <li class="question-choice">{{choice3}}</li>
                <li class="question-choice">{{choice4}}</li>
            </ul>
        </nav>
        <section class="result p-2 clearfix">
            <div class="span float-start"></div>
            <div class="span float-end">
                <button class="btn btn-primary btn-sm">Next</button>
            </div>
        </section>

    </script>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0/handlebars.js"></script>
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="assets/index.js"></script>
</body>
</html>