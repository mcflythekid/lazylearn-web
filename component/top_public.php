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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?=$TITLE?></title>

        <style>
            .public-container{
                margin-top: 20px;
            }
        </style>

        <!-- Bootstrap core CSS -->
        <link href="../node_modules/startbootstrap-heroic-features/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="../node_modules/startbootstrap-heroic-features/css/heroic-features.css" rel="stylesheet">


        <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>

        <!-- jquery -->
        <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
        <!-- axios -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.17.1/axios.js"></script>

        <script>
            var ctx = '<?=$CTX?>';
            var endpoint = '<?=$ENDPOINT?>';
        </script>

        <script src="<?=$ASSET?>/tool.js"></script>
        <script src="<?=$ASSET?>/app.js"></script>

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

            FB.AppEvents.logPageView();

            FB.getLoginStatus(function(response) {
                console.log(response.authResponse.accessToken);
                alert(JSON.stringify(response));
            });

        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <script>
        $().ready(()=>{

        });

    </script>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">Lazylearn</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collaps navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/login.php">Login</a>
                    </li>


                    <li class="nav-item active">
                        <a class="nav-link" href="/register.php">Register</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">


<?php } ?>
