jQuery.noConflict();
 jQuery(document).ready(function(){
		jQuery('.tab-content .tab-pane').hide();
		jQuery('.tab-content .active').show();
        jQuery('.nav-tabs li a').click(function(){
           var li = jQuery(this).parent();
           if(jQuery(li).hasClass('active')) return false;
           var tab = jQuery(this).attr('href');
           jQuery('.nav-tabs li.active').removeClass('active');
           jQuery(li).addClass('active');
           
           jQuery('.tab-pane.active').hide().removeClass('active');
           jQuery(tab).addClass('active').show();
           return false;
        });	
		//Jquery mouseover width demo and getcode demo
		jQuery('td.viewwidget').css({'background':'ThreeDLightShadow','color':'#666666'});			
						
		jQuery('td.viewwidget').bind({
			mouseenter: function() {
				jQuery('td.viewwidget').css({'background':'ThreeDLightShadow','color':'#666666'});		
				jQuery('.widgetdemo').css('position','relative');				
				jQuery(this).css({'background':'#d6cadd','color':'#FFFFFF'});//#C6BCBE
				jQuery(this).find('.imagehover img').css({'cursor':'pointer'});
				jQuery(this).find('.imagehover span').css({'text-decoration':'none'});	

			},
			mouseleave:function(){		
				
				jQuery(this).css({'background':'#d6cadd','color':'#FFFFFF'});//#C6BCBE		
				
			}
		});
	
	jQuery('#socialaccount #users-profile').addClass('bt-nomal');	
	jQuery('#socialaccount #users-profile').bind({
		mouseenter: function() {	
			jQuery(this).removeClass('bt-nomal');
			jQuery(this).addClass('bt-hover');
		},
		mouseleave:function(){	
			jQuery(this).removeClass('bt-hover');
		}
	});
});