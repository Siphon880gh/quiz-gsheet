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


    <!-- jQuery and Bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>
    
    <div class="container-fluid">
        <header class="site-header clearfix">
            <h1 class="site-title float-start">Music Quiz</h1>
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
            <article class="intro">
                <h2 class="intro-title">Questions</h2>
                <p class="intro-description">Play a quick quiz game to test your sight reading skills in music.</p>
                <button onclick="initQuestionsUI()">Start</button>
            </article>
            <article class="question">
                <h2 class="question-title">Q Title</h2>
                <p class="question-description">Q Desc</p>
                <nav class="question-choices">
                    <ul>
                        <li class="question-choice">a</li>
                        <li class="question-choice">b</li>
                        <li class="question-choice">c</li>
                        <li class="question-choice">c</li>
                    </ul>
                </nav>
            </article>
            <article class="intro">
                <h2 class="finish-title">Finished!</h2>
                <p class="finish-description">Thanks for playing.</p>
                <section class="finish-score">F score</section>
            </article>
        </main>
    </div> <!-- Ends container-fluid -->
    
    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="assets/index.js"></script>
</body>
</html>