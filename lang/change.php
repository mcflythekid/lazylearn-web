<?php
	session_start();
	if (!isset($_GET["lang"])){
		header("Location: /");
		die();
	}
	$lang = $_GET["lang"];
	$back = isset($_GET["back"]) ? $_GET["back"] : "/";
	

	
	// update session_cache_expire
	$_SESSION["lang"] = $lang;
	
	// update cookie
	setcookie("lang", $lang, 2147483647);
	
	header("Location: $back");
	die();