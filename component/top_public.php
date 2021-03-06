<?php 
function top_public($showMenu = true){
    global $lang;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <style>
        body {
            padding-top: 50px;
        }
    </style>
    <?=asset()?>
    <script>
        Application.publicPagesCheck();
    </script>
</head>
<body>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '226440184828839',
                cookie     : true,
                xfbml      : true,
                version    : 'v3.0'
            });
        };
        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        /**
         * Language change
         */
        $(document).on('click', 'a.lang', function(event){
            event.preventDefault();
            const lang = $(this).attr('data-lang');
            axios.get("/lang/change.php?lang=" + lang).finally(()=>{
            location.reload();
            });
        });
    </script>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><strong>LazyLearn</strong></a>
            </div>

            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php if ($showMenu) { ?>
                    <li><a href="/auth/register.php"><strong><?= $lang["layout.public.register"] ?></strong></a></li>
                    <li><a href="/auth/login.php"><strong><?= $lang["layout.public.login"] ?></strong></a></li>
                    <?php } ?>

                </ul>
            </div><!--/.nav-collapse -->

        </div>
    </nav>

    <div class="container">
<!------------------------------------------------------------------------------------------------------------------->
<?php } ?>
