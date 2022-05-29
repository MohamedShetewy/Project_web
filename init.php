<?php
	
	//Error Repporting

	ini_set('display_errors','Off');
	error_reporting(E_ALL);

	$sessionUser = '';

	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
    }

	include 'admin/connect.php';
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
	
	