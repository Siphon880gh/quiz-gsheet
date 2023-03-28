<?php
/* Setup Session for pathing */
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

function glob_recursive($pattern, $flags = 0)
{
    $files = glob($pattern, $flags);
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
{
    $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
}
    return $files;
}

$gsheetPaths = glob_recursive("gsheets/*\.php");
$gsheetNames = [];
for($i=0; $i<count($gsheetPaths); $i++) {
    $gsheetPath = $gsheetPaths[$i];
    preg_match("/gsheets\/(.*)\.php$/", $gsheetPath, $matches);
    if($matches[1]) $gsheetName = $matches[1];
    array_push($gsheetNames, $gsheetName); // $gsheetName is "<folder>/<filename>"
} // for
// var_dump($gsheetNames);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>

    <!-- Styling  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $_SESSION["root_url"] . "public/" ?>assets/index.css">

    <script>
    // PHP brings in Google Sheet Data directly is faster
    window.dirs = `<?php echo json_encode($gsheetNames); ?>`;
    window.dirs = JSON.parse(window.dirs)
    </script>

</head>

<body>
    <?php
        // Bootstrap annoyingly removed Jumbotron, so to improve readability:
        $jumbo = "container bg-light text-start px-5 py-4 rounded-3 my-4";
    ?>
    
    <div class="container-fluid">
        <header class="site-header clearfix">
            <h1 class="site-title display-3 float-start">Quiz</h1>
            <!-- <nav class="site-nav float-end">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" target="_blank">
                            Github
                        </a>
                    </li>
                </ul>
            </nav> -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/2.0.0/handlebars.js"></script>
    <script src="<?php echo $_SESSION["root_url"] . "public/" ?>assets/index.js"></script>
</body>
</html>