$(document).ready(function () {
	// Strict Mode
	"use strict";

	//Defines variables	
	var arrow_up = '<i class="fa fa-angle-up" aria-hidden="true"></i>';
	var arrow_down = '<i class="fa fa-angle-down" aria-hidden="true"></i>';
	var arrow_span = '<button type="button" class="rs-menu-parent" aria-label="Toggle submenu" aria-expanded="false">' + arrow_down + '</button>';
	var close_button = '<div class="sub-menu-close"><i class="fa fa-times" aria-hidden="true"></i>Close</div>';
	
	//Insert all arrow down span element
	$('.nav-menu .rs-mega-menu').append(arrow_span);
	$('.nav-menu > .menu-item-has-children').append(arrow_span);
	$('.nav-menu > .menu-item-has-children .sub-menu > .menu-item-has-children').append(arrow_span);
	
	//Insert all close button element
	$('.nav-menu .menu-item-has-children .sub-menu').append(close_button);
	$('.nav-menu .rs-mega-menu .mega-menu').append(close_button);

	/*-----------------------------------------------------------------------------------*/
	/*	OPEN SUB MENU FUNCTION
	/*-----------------------------------------------------------------------------------*/
	$('button.rs-menu-parent').on('click', function(e){
		e.preventDefault();
		
		var t = $(this);
		var menu = t.siblings('ul');	
		var parent = t.parent('li');
		var siblings = parent.siblings('li');
		var arrow_target = 'button.rs-menu-parent';
		
		if (menu.hasClass('sub-menu')) { 
			var menu = t.siblings('ul.sub-menu'); 
		} else if(menu.hasClass('mega-menu')) {
			var menu = t.siblings('ul.mega-menu');
		}
		
		if (menu.hasClass('visible')) {
			setTimeout(function() { menu.removeClass('visible'); }, 10);	
			t.html(arrow_down).attr('aria-expanded', 'false');
		} else {
			setTimeout(function() { menu.addClass('visible'); }, 10);
			t.html(arrow_up).attr('aria-expanded', 'true');
		}
			
		/*-------------------------------------*/
		/*	CLOSE MENUS
		/*-------------------------------------*/
			
		//Close sub menus
		parent.find('ul.visible').removeClass('visible');	
		
		//Close sub menus parents
		parent.siblings('li').children('ul').removeClass('visible');	
		
		//Close sub menus child parents 
		siblings.find('ul.visible').removeClass('visible');	
		
		/*-------------------------------------*/
		/*	INSERT ARROW DOWN
		/*-------------------------------------*/	
		
		//Insert arrow down in sub menus
		parent.children('ul').find(arrow_target).html(arrow_down).attr('aria-expanded', 'false');
		
		//Insert arrow down in sub menus parents
		siblings.children(arrow_target).html(arrow_down).attr('aria-expanded', 'false');
		
		//Insert arrow down in sub menus child parents 
		siblings.find(arrow_target).html(arrow_down).attr('aria-expanded', 'false');
	}); 

	$('.rs-menu-link').on('click keydown', function(e){
		if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') {
			return;
		}
		e.preventDefault();
		var control = $(this).siblings('button.rs-menu-parent');
		if (control.length) {
			control.trigger('click');
			$(this).attr('aria-expanded', control.attr('aria-expanded'));
		}
	});
	
	/*-----------------------------------------------------------------------------------*/
	/*	CLOSE BUTTON
	/*-----------------------------------------------------------------------------------*/ 
	$('ul.nav-menu div.sub-menu-close').on('click', function(e){
	   e.preventDefault();
		  
	   var a = $(this).parent('ul');      
	   a.removeClass('visible');
	   a.siblings('span.rs-menu-parent').html(arrow_down);
	}); 
	
	/*-----------------------------------------------------------------------------------*/
	/*	EFFECTS ON MENU TOGGLE
	/*-----------------------------------------------------------------------------------*/ 
	$('.rs-menu-toggle').on('click', function(e){
		e.preventDefault();	
		var menu_height = $('.rs-menu ul').height();
		
		if ($(this).hasClass('rs-menu-toggle-open')) {		
			$(this).removeClass('rs-menu-toggle-open').addClass('rs-menu-toggle-close').attr('aria-expanded', 'false');
			$('.rs-menu').animate({height:'0px'},{queue:false, duration:300}).addClass('rs-menu-close');	
		} else {			
			$(this).removeClass('rs-menu-toggle-close').addClass('rs-menu-toggle-open').attr('aria-expanded', 'true');
			$('.rs-menu').animate({height:menu_height},{queue:false, duration:300}).removeClass('rs-menu-close');
		}
	});	
	
	/*-----------------------------------------------------------------------------------*/
	/*	CLOSE MENUS ON RESIZE
	/*-----------------------------------------------------------------------------------*/ 
	var window_width = 0;
	 
	$(window).on('load', function () {	
		window_width = $(window).width();
		$('.rs-menu').addClass( "rs-menu-close" );
	});
	
	$(window).resize( function(){    
		if(window_width !== $(window).width()){		
			$('.visible').removeClass('visible');	
			$('.rs-menu-toggle').removeClass('rs-menu-toggle-open').addClass( "rs-menu-toggle-close" ).attr('aria-expanded', 'false');
			$('.rs-menu').css( "height", "0" ).addClass( "rs-menu-close" );		
		
			$('span.rs-menu-parent').html( arrow_down );		
			window_width = $(window).width();	
		}
	});	
	
});