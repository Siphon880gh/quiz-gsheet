<?php
/* SETUP: Initialize pathing with PHP Session */
session_start();
function getHttpHttps() {
    $isHttps =
      (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || $_SERVER['SERVER_PORT'] == 443;
    if($isHttps)
        return "https://";
    return "http://";
}
$_SESSION["root_dir"] = __DIR__;
$_SESSION["root_url"] = getHttpHttps() . ($_SERVER["HTTP_HOST"]) . $_SERVER['REQUEST_URI'];

// Wrangle away URL queries
$string = $_SESSION["root_url"] ;
$pattern = '/(.*)\?+.*/i';
$replacement = '${1}';
$_SESSION["root_url"] = preg_replace($pattern, $replacement, $string);

/* Scenario: User had visited subpage without initializing. Had forced redirect here. Now redirect to callback URL */
$usingCallback = isset($_GET["callback"]);
if($usingCallback) {
    $callbackUrl = $_GET["callback"];
    header("Location: $callbackUrl");
    exit();

}

    $relativePathing = ".";

    /* SETUP: Setup Google Sheet listing */
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        // foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR) as $dir)
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
    {
        $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
        return $files;
    }

    function is_password_protected($token) {
        // return strpos($token, "gsheets/-")!=false; // Oddly glitches
        $res = strpos($token, "gsheets/-");
        if($res>-1) {
            return true;
        } else {
            return false;
        }
    }
    function is_not_password_protected($token) {
        $res = strpos($token, "gsheets/-");
        if($res>-1) {
            return false;
        } else {
            return true;
        }
    }
    function is_this_password($token) {
        GLOBAL $password;
        $res = strpos($token, "gsheets/-$password");
        if($res>-1) {
            return true;
        } else {
            return false;
        }
    }
    
    $gsheetPaths = glob_recursive("$relativePathing/gsheets/*\.php");
    $gsheetPaths = array_values(array_filter($gsheetPaths, 'is_not_password_protected'));
    // var_dump($gsheetPaths);

    $gsheetNames = [];
    for($i=0; $i<count($gsheetPaths); $i++) {
        $gsheetPath = $gsheetPaths[$i];
        $j = strpos($gsheetPath, "gsheets/");
        if($j!=false) {
            $substringed = substr($gsheetPath, $j+strlen("gsheets/")); // substringed away ./gsheets/ or ../gsheets/
            $substringed = substr($substringed,0, strlen($substringed)-strlen(".php"));
            array_push($gsheetNames, $substringed); // $gsheetName is "<folder>/<filename>"
        }
    } // for

    // var_dump($gsheetNames);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="By Weng Fei Fung. Take various quizzes for learning purposes. Currently music sight reading lessons. Coming soon - ICU topics.">
    <meta property="og:description" content="By Weng Fei Fung. Take various quizzes for learning purposes. Currently music sight reading lessons. Coming soon - ICU topics." />
    <meta property="og:title" content="Quizzes" />
    <!-- <meta property="og:image" content="TODO//" /> -->

    <title>Quiz</title>

    <!-- Styling  -->
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.min.css" rel="stylesheet">
    <link href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/index.css">

    <script>
    // PHP brings in Google Sheet Data directly is faster
    try {
        window.dirs = `<?php echo json_encode($gsheetNames); ?>`;
        window.dirs = JSON.parse(window.dirs)
    } catch(err) {
        console.error({error:err, message: "To web developer: If error in JSON, then get the JSON from DevTools and copy it to Online JSON Editor. The top line it errors on is where the problem is, likely a character that is not recognized. You can immediately test in Online JSON Editor."})
    }
    </script>

</head>

<body>
    <?php
        // Bootstrap annoyingly removed Jumbotron, so to improve readability:
        $jumbo = "container bg-light text-start px-5 py-4 rounded-3 my-4";
    ?>
    
    <div class="container-fluid">
        <header class="site-header text-center">
            <nav class="site-nav float-end">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" onclick="addQuizzesFromPassword()">
                            🔑 Passwords
                        </a>
                    </li>
                </ul>
            </nav>
            <h1 class="clearfix site-title display-3">Quiz</h1>
            <p>By Weng Fei Fung<br/>These questions are managed from Google Sheets so it's easy to add more questions.<br/><a href="https://wengindustry.com/me/contact" target="_blank">Suggest quizzes</a> or <a href="https://github.com/Siphon880gh/quiz-gsheet" target="_blank">see my Github</a> if you want to implement your own quizzes or contribute to the app.</p>

        </header>

        <main class="site-body">
            <article class="intro <?php echo $jumbo; ?>" data-page=0>
                <!-- <h2 class="intro-title display-5">Choose a quiz:</h2> -->
                <section class="dirs-wrapper my-4">
                    <ul class="dirs">

                    </ul>
                </section>
            </article>
        </main>
    </div> <!-- Ends container-fluid -->
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/handlebars@2.0.0/dist/handlebars.min.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/index.js"></script>
</body>
</html>