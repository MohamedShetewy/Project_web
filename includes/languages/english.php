<?php

	 function lang( $phrase){

	 	static $lang = array(

	 		//Dashboard page 

	 		'HOME_ADMIN' => 'Home',
	 		'ADMIN' => 'Adminstration'


	 		 );
	 	return $lang[$phrase]  ;
	 }