<?php

	 function lang( $phrase){

	 	static $lang = array(

	 		'Home' => 'رئيسية',
	 		'ADMIN' => ' arbic Admin'

	 		 );
	 	return $lang[$phrase]  ;
	 }