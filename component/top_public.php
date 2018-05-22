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
        <link rel="icon" href="<?=$ASSET?>/img/owl-icon.png">

        <title><?=$TITLE?></title>

        <!-- Bootstrap core CSS -->
        <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?=$ASSET?>/external/justified-nav.css" rel="stylesheet">


        <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>

        <!-- jquery -->
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.min.css">

        <!-- bootstrap -->
<!--        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >-->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- axios -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.17.1/axios.js"></script>

        <!-- Include the Quill library -->
        <link href="//cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">
        <script src="//cdn.quilljs.com/1.3.4/quill.js"></script>

        <!-- bootstrap dialog -->
        <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet">
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- bootstrap table -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>


        <!-- app -->
        <link rel="stylesheet" href="<?=$ASSET?>/app.css">


        <script>
            var ctx = '<?=$CTX?>';
            var endpoint = '<?=$ENDPOINT?>';
        </script>
        <script src="<?=$ASSET?>/tool.js"></script>
        <script src="<?=$ASSET?>/app.js"></script>
        <script src="<?=$ASSET?>/external/quill-magic-url.min.js"></script>
        <script src="<?=$ASSET?>/external/image-resize.min.js"></script>

    </head>

    <body>

    <div class="container">

        <!-- The justified navigation menu is meant for single line per list item.
             Multiple lines will require custom code not provided by Bootstrap. -->
        <div class="masthead">
<!--            <h3 class="text-muted">Lazylearn</h3>-->
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
        <style>
            .masthead{
                margin-bottom: 20px;
            }
        </style>


<?php } ?>
