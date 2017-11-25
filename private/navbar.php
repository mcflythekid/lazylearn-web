<meta charset="UTF-8">

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=764639117052467";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



<?php 

	function isShowQc(){
		return false;
		$scriptName = basename($_SERVER['PHP_SELF']);
		if ($scriptName == "login.php") {return false;}
		if ($scriptName == "signup.php") {return false;}
		if ($scriptName == "recovery.php") {return false;}
		return true;
	}



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
	} elseif (isset($_SESSION["username"]) && ($uri === "/user.php?id=" . $_SESSION["username"] || $uri === "/user/" . $_SESSION["username"])) {
		$my_class = "sel";
	}
	if (isset($_SESSION["username"])){
?>
	<div id="navlinks">
		<a href="/"><img alt="Free Flashcard Website" src="<?php echo $ASSET; ?>/img/banner.png"></a>
		<a id="top_banner" href="https://www.vultr.com/?ref=6833778"><img class="inner-banner-image" src="//www.vultr.com/media/468x60_02.gif"></a>
		<br class="clearboth">
		<div id="nav_main">
			<form method="post" name="logout" action="/logout.php"></form>
			<div id="nav_main_left">
				<!--Welcome, <?php echo $_SESSION['username']; ?> |-->
				<a href="/setting.php"><strong><?=$lang["navbar"]["setting"] ?></strong></a> | <a href="javascript:document.logout.submit()"><strong><?=$lang["navbar"]["logout"] ?></strong></a>
			</div>
			
			<div id="nav_main_right">
				<a href="/lang/change.php?lang=vi&back=<?=$_SERVER['REQUEST_URI']?>">
					<img src="<?=$ASSET?>/img/flag/vi.gif">
				</a>
				<a href="/lang/change.php?lang=vi&back=<?=$_SERVER['REQUEST_URI']?>">
					Việt Ngữ
				</a>&nbsp;&nbsp;
				
				<a href="/lang/change.php?lang=en&back=<?=$_SERVER['REQUEST_URI']?>">
					<img src="<?=$ASSET?>/img/flag/en.gif">
				</a>
				<a href="/lang/change.php?lang=en&back=<?=$_SERVER['REQUEST_URI']?>">
					English
				</a>
			</div>

			
		</div>
	</div>
	<div id="navbar">
		<ul>
			<li><a class="<?php echo $home_class; ?>" href="/"><?=$lang["navbar"]["home"] ?></a></li>
			<li><a class="<?php echo $my_class; ?>" href="/user/<?php echo $_SESSION['username']; ?>"><?php echo $_SESSION['username']; ?></a></li>
			<li><a class="<?php echo $new_class; ?>" href="/flashcard/new.php"><?=$lang["navbar"]["new_set"] ?></a></li>
		</ul>
		<div id="nav_search">
			<form id="cse-search-box" action="/search" accept-charset="UTF-8">
				<div>
				  <input name="q" size="31" id="cse-search-field" value="<?php if (isset($search)){echo $search;} ?>">
				  <input type="submit" value="<?=$lang["navbar"]["search"] ?>">
				</div>
			</form>
		 </div>
	</div>
<?php } else { ?>

	<?php if (isShowQc()){ ?>
	<!-- AdMashMedia.com Universal Ad Tag -->
	<script type="text/javascript" src="http://radiumnetwork.net/radiumserver/xtaguniversal.js.php?aim1=8536&aim2=11874"></script>    
	<?php } ?>
	
	<div id="navlinks">
		<a href="/"><img alt="Free Flashcard Website" src="<?php echo $ASSET; ?>/img/banner.png"></a>

		<?php if (isShowQc()){ ?>
		<a id="top_banner">
			<!-- AdMashMedia.com Banner Tag 468x60 -->
			<script type="text/javascript" src="http://radiumnetwork.net/radiumserver/xbanner.js.php?aim1=8536&aim2=11874&aim3=4"></script>
		</a>
		<?php } else { ?>
		<a id="top_banner" href="https://www.vultr.com/?ref=6833778"><img class="inner-banner-image" src="//www.vultr.com/media/468x60_02.gif"></a>
		<?php } ?>
		
		<br class="clearboth">
		<div id="nav_main">
			<div id="nav_main_left">
				<a href="/signup"><strong><?=$lang["navbar"]["register"] ?></strong></a> | <a href="/login"><strong><?=$lang["navbar"]["login"] ?></strong></a>
			</div>
			
			
			<div id="nav_main_right">
				<a href="/lang/change.php?lang=vi&back=<?=$_SERVER['REQUEST_URI']?>">
					<img src="<?=$ASSET?>/img/flag/vi.gif">
				</a>
				<a href="/lang/change.php?lang=vi&back=<?=$_SERVER['REQUEST_URI']?>">
					Việt Ngữ
				</a>&nbsp;&nbsp;
				
				<a href="/lang/change.php?lang=en&back=<?=$_SERVER['REQUEST_URI']?>">
					<img src="<?=$ASSET?>/img/flag/en.gif">
				</a>
				<a href="/lang/change.php?lang=en&back=<?=$_SERVER['REQUEST_URI']?>">
					English
				</a>
			</div>

			
		</div>
	</div>
        
	<div id="navbar">
		<ul>
			<li><a class="<?php echo $home_class; ?>" href="/"><?=$lang["navbar"]["home"] ?></a></li>
		</ul>
		<div id="nav_search">
			<form id="cse-search-box" action="/search" accept-charset="UTF-8">
				<div>
				  <input name="q" size="31" id="cse-search-field" value="<?php if (isset($search)){echo $search;} ?>">
				  <input type="submit" value='<?=$lang["navbar"]["search"]?>'>
				</div>
			</form>
		 </div>
	</div>		
<?php } ?>