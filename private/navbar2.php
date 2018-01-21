<?php
	function startsWith($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}

	$uri = $_SERVER['REQUEST_URI'];
	$home_class = $my_class = $browse_class = $new_class = $tdog_class = "";
	
	if (isset($_GET["q"]) && startsWith($uri, "/search")){
		$search = noHTML($_GET["q"]);
	}
	
	if ($uri === "/") {
		$home_class = "sel";
	} elseif ($uri === "/flashcard/new.php") {
		$new_class = "sel";
	} elseif ($uri === "/flashcard/") {
		$browse_class = "sel";
	} elseif (isset($_SESSION["username"]) && ($uri === "/user.php?id=" . $_SESSION["username"] || $uri === "/user/" . $_SESSION["username"])) {
		$my_class = "sel";
	}
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

    <title>Navbar Template for Bootstrap</title>

	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/start/jquery-ui.min.css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<style>
	.navbar-static-top {
	  margin-bottom: 19px;
	}
	</style>
	
	<script>
	function flash(type,content){
		if($('#wrapperShowHide').length==0){
			var wrapper = '<div id="wrapperShowHide" style="width: 300px;top:5%; left:69%;position: fixed;z-index:10000; font-size:13px"></div>';
			$('body').before(wrapper);
		}
		var count=0;
		while($('#showHideMessage'+count).length>0){
			count++;
		}
		var messageType="";
		var header="";
		switch(type) {
			case 1:
				messageType="alert-success";
				header="";
				break;
			case 2:
				messageType="alert-info";
				header="";
				break;
			case 3:
				messageType="alert-warning";
				header="Cảnh báo! ";
				break;
			default:
				messageType="alert-danger";
				header="Lỗi! ";
		}
		var content = '<div class="alert '+messageType+'" id="showHideMessage'+count+'" style="width: 400px;"><button type="button" class="close" data-dismiss="alert">x</button><strong>'+header+'</strong>'+content+'</div>';
		$("#wrapperShowHide").append(content);
		
		$("#showHideMessage"+count).fadeTo(5000, 1500).fadeOut(1000, function(){
			$(this).alert('close');
			if($('#wrapperShowHide > div').length==0){
				$('#wrapperShowHide').remove();
			}
		});   
	}
	</script>
	
	<link rel="stylesheet" href="<?php echo $ASSET?>/css/style2.css">
	<link rel="stylesheet" href="<?php echo $ASSET?>/css/docs.min.css">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.17.1/axios.js"></script>
	
	<!-- Include the Quill library -->
	<link href="https://cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">
	<script src="https://cdn.quilljs.com/1.3.4/quill.js"></script>
	
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  
 <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">LAZYLEARN</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

			<ul class="nav navbar-nav">
					<li><a class="<?php echo $browse_class; ?>" href="/flashcard"><?=$lang["navbar"]["browse"] ?></a></li>
				<?php  if (isset($_SESSION["username"])){ ?>
					<li><a class="<?php echo $my_class; ?>" href="/user/<?php echo $_SESSION['username']; ?>">Dashboard</a></li>
					<li><a class="<?php echo $new_class; ?>" href="/flashcard/new.php">Create</a></li>
				<?php } else { ?>
				<?php } ?>
					<li><a href="https://pair.lazylearn.com" >Minimum Pairs</a></li>
			</ul>

          <ul class="nav navbar-nav navbar-right">
		<?php  if (isset($_SESSION["username"])){ ?>
			<form method="post" name="logout" action="/logout.php"></form>
			<li><a href="/setting.php"><?=$lang["navbar"]["setting"] ?></a></li>
			<li><a href="javascript:document.logout.submit()"><?=$lang["navbar"]["logout"] ?></a></li>
		<?php } else { ?>
			<li><a href="/signup"><?=$lang["navbar"]["register"] ?></a></li>
			<li><a href="/login"><?=$lang["navbar"]["login"] ?></a></li>
		<?php } ?>
          </ul>
			
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
  
    <div class="container">


		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=764639117052467";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>