<?php
session_start();
$lang = $_GET["lang"];
$_SESSION["lang"] = $lang;
echo "Selected language: " . $lang;