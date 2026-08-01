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
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
$_SESSION["root_url"] = getHttpHttps() . $_SERVER["HTTP_HOST"] . $scriptDir;

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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=Source+Serif+4:opsz,wght@8..60,500;8..60,600&display=swap" rel="stylesheet">

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
    <div class="page-shell">
        <header class="site-header">
            <nav class="site-nav">
                <button type="button" class="nav-unlock" onclick="addQuizzesFromPassword()">
                    <i class="ri-key-2-line" aria-hidden="true"></i>
                    Passwords
                </button>
            </nav>

            <div class="hero">
                <h1 class="site-title">Quiz</h1>
                <p class="site-lede">
                    Business, medicine, and music quizzes generated from Google Sheets.
                    <a href="https://wengindustry.com/me/contact" target="_blank" rel="noopener">Suggest a topic</a>
                    or browse the
                    <a href="https://github.com/Siphon880gh/quiz-gsheet" target="_blank" rel="noopener">repo</a>.
                </p>
            </div>
        </header>

        <main class="site-body">
            <article class="intro" data-page="0">
                <section class="dirs-wrapper">
                    <div class="category-filter-wrap">
                        <label for="category-filter" class="filter-label">Filter</label>
                        <select id="category-filter" class="category-filter" aria-label="Filter by category">
                            <option value="">All topics</option>
                        </select>
                    </div>
                    <ul class="dirs"></ul>
                </section>
            </article>
        </main>

        <footer class="site-footer">
            <details class="credits">
                <summary class="credits-toggle">Credits</summary>
                <div class="credits-panel">
                    <a class="credits-author" href="mailto:weng.f.fung@gmail.com">Weng Fei Fung</a>
                    <div class="credits-links">
                        <a target="_blank" href="https://github.com/Siphon880gh" rel="noopener nofollow">
                            <img src="https://img.shields.io/badge/GitHub--blue?style=social&logo=GitHub" alt="GitHub" style="max-width:10ch; vertical-align: middle;">
                        </a>
                        <a target="_blank" href="https://www.linkedin.com/in/weng-fung/" rel="noopener nofollow">
                            <img src="https://img.shields.io/badge/LinkedIn-blue?style=flat&logo=linkedin&labelColor=blue" alt="LinkedIn" style="max-width:10ch; vertical-align: middle;">
                        </a>
                        <a target="_blank" href="https://www.youtube.com/@WayneTeachesCode/" rel="noopener nofollow">
                            <img src="https://img.shields.io/badge/Youtube-red?style=flat&logo=youtube&labelColor=red" alt="YouTube" style="max-width:10ch; vertical-align: middle;">
                        </a>
                    </div>
                </div>
            </details>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/handlebars@2.0.0/dist/handlebars.min.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/index.js"></script>
</body>
</html>