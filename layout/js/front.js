$(function () {

	'use strict';

	//Switch Between Login & Signup
	$('.login-page h1 span').click(function(){

		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide();

		$('.'+$(this).data('class')).fadeIn(100);


	
	});

	// trigger Selectbox
	  $("select").selectBoxIt({

	  	autoWidth: false
	  });

	
	// Hide place holder on form focus 
	$('[placeholder]').focus(function(){
		$(this).attr('data-text',$(this).attr('placeholder'));
		$(this).attr('placeholder','');

	}).blur(function(){
		$(this).attr('placeholder',$(this).attr('data-text'));
	})

	//add Asterisk on required field

	$('input').each(function(){

		if ($(this).attr('required') === 'required') {

			$(this).after('<span class="asterisk">*</span>');
		}

	});


	//confirmation massage on button
	$('.confirm').click(function(){
		return confirm('Are you sure?');
	});

	$('.live').keyup(function(){


		$($(this).data('class')).text($(this).val());

	});



});