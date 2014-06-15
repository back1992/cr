jQuery.noConflict();
 jQuery(document).ready(function(){
	jQuery('#submenu li a.active').parents('li').addClass('active');  
	
	jQuery('.user_setting').height(jQuery('.user_setting .cpanel').height());
	jQuery('.social_setting').height(jQuery('.social_setting .cpanel').height());
	jQuery('.extension_setting').height(jQuery('.extension_setting .cpanel').height());
	jQuery('.widget_setting').height(jQuery('.widget_setting .cpanel').height());
	
	jQuery('.toogle-user').click(function(){
		jQuery('.user_setting').stop(true,true).slideToggle(300,"linear");
	});
	
	jQuery('.toogle-social').click(function(){
		jQuery('.social_setting').stop(true,true).slideToggle(300,"linear");
	});
	
	jQuery('.toogle-extension').click(function(){
		jQuery('.extension_setting').stop(true,true).slideToggle(300,"linear");
	});
	
	jQuery('.toogle-widget').click(function(){
		jQuery('.widget_setting').stop(true,true).slideToggle(300,"linear");
	});
      
	jQuery('#submenu li').eq(0).addClass('sub-panel');  
	jQuery('#submenu li').eq(1).addClass('sub-user');  
	jQuery('#submenu li').eq(2).addClass('sub-userfield'); 		 	
	jQuery('#submenu li').eq(3).addClass('sub-cloud');  
	jQuery('#submenu li').eq(4).addClass('sub-joomla');  
	jQuery('#submenu li').eq(5).addClass('sub-log');  
	jQuery('#submenu li').eq(6).addClass('sub-statistic'); 
 });
 