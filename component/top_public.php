<?php 
function top_public(){
	global $TITLE;
	global $ASSET;
	global $CTX;
	global $ENDPOINT;
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title>Justified Nav Template for Bootstrap</title>

        <!-- Bootstrap core CSS -->
        <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">



        <!-- Custom styles for this template -->
        <link href="<?=$ASSET?>/external/justified-nav.css" rel="stylesheet">

    </head>

    <body>

    <div class="container">

        <!-- The justified navigation menu is meant for single line per list item.
             Multiple lines will require custom code not provided by Bootstrap. -->
        <div class="masthead">
            <h3 class="text-muted">Lazylearn</h3>
            <nav>
                <ul class="nav nav-justified">
                    <li><a href="/">Home</a></li>
                    <li><a href="/register.php">Register</a></li>
                    <li><a href="/login.php">Login</a></li>
                    <li><a href="/forget-password.php">Recovery</a></li>
                    <li><a href="https://github.com/mcflythekid/lazylearn-api" target="_blank">Github</a></li>
                </ul>
            </nav>
        </div>



<?php } ?>
