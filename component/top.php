<?php 
function top(){ 
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
    <link rel="icon" href="<?=$ASSET?>/img/ico48.png">
    <title><?=$TITLE?></title>

    <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>

	<!-- jquery -->
	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.min.css">

	<!-- bootstrap -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
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
	<link rel="stylesheet" href="<?=$ASSET?>/external/concu/css/concu.css">


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

 <!-- Static navbar -->
    <nav id="menu" class="navbar navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/">
              <img alt="" src="<?=$ASSET?>/img/ico48.png" class="img-logo">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

			<ul class="nav navbar-nav">
				<li class="ui--in" style="background: #2980b9;"><a style="color : #FFF;" href="/">DECK</a></li>
				<li class="ui--in"><a href="/hyper-deck.php">VOCABULARY</a></li>
			</ul>

          <ul class="nav navbar-nav navbar-right">
			<li class="ui--out"><a href="/register.php">Register</a></li>
			<li class="ui--out" ><a href="/login.php">Login</a></li>
			<li class="dropdown ui--in">
<!--				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">-->
<!--					<span class="email"></span> <span class="caret"></span>-->
<!--				</a>-->
<!--				<ul class="dropdown-menu">-->
					<li class="ui--in"><a href="/change-password.php">Option</a></li>
					<li class="ui--in"><a href="#" id="logout">Logout</a></li>									
<!--				</ul>-->
			</li>			
          </ul>
			
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<script>
	(()=>{
        $('#logout').click((event)=>{
            event.preventDefault();
            $app.logout();
        });

        var auth = $tool.getData('auth');
        if (auth){
            $('.navbar-static-top .email').text(auth.email);
            $('.ui--in').show();
            $('.ui--out').hide();
        }else {
            $('.ui--in').hide();
            $('.ui--out').show();
        }
	})();
	</script>
	
  
    <div class="container">
<?php } ?>