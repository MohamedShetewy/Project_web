<?php

	include 'connect.php';
	//routes

	$tpl  = "includes/templates/";
	$lang = "includes/languages/";
	$func = "includes/functions/";
	$css  = "layout/css/";
	$js   = "layout/js/";


	//include important file

	include $func ."functions.php";
	include $lang . "english.php";
	include $tpl . "header.php";

	if (!isset($nonavbar)) {

		include $tpl . "navbar.php";

	}