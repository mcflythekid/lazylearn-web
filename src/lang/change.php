<?php
session_start();
$lang = $_GET["lang"];

$_SESSION["lang"] = $lang;
setcookie("lang", $lang, time() + (10 * 365 * 24 * 60 * 60), "/");

echo "Selected language: " . $lang;