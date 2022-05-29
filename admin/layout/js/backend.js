$(function () {

	'use strict';

	//Dashbard

	  $('.toggle-info').click(function(){

	  	$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

	  	if($(this).hasClass('selected')){
	  		$(this).html('<i class="fa fa-minus fa-lg"></i>');
	  	}else{
	  		$(this).html('<i class="fa fa-plus fa-lg"></i>');
	  	}

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

   // conver password field to text field on hover

   var passfield = $('.password');
	$('.show-pass').hover(function(){

		passfield.attr('type','text');

	},function(){
		passfield.attr('type','password');
	});

	//confirmation massage on button
	$('.confirm').click(function(){
		return confirm('Are you sure?');
	});

	//Categorie view option

	$('.cat h3').click(function(){
		$(this).next('.full-view').fadeToggle(200);
	});

	$('.option span').click(function(){
		$(this).addClass('active').siblings('span').removeClass('active');
		if($(this).data('view')==='full'){
			$('.cat .full-view').fadeIn(200);
		}else{
			$('.cat .full-view').fadeOut(200);
		}
	});

	//show Delete Button on child Cats

	$('.child-link').hover(function(){

		$(this).find('.show-delete').fadeIn(400);

	},function(){
		$(this).find('.show-delete').fadeOut(400);
	});

});