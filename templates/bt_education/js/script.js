(function($){
$(document).ready(function(){

	$('.top_mainbody_content .classEffect').each(function(index){
			$(this).waypoint(function(){
				$(this).delay(300*index+300).animate({
					width: "auto"
				}, 0, function(){
					if (isIE () == 9) {
						$(this).animate({opacity: 1}); // apply opacity animation for ie9
					} else {
						$(this).addClass('move-up'); // apply move up for modern browsers
					}
				});
			}, { offset: '100%' });
	});

	// get in touch
	$('.icon-click').click(function(){
		var hGetInTouch = $('.moduletable.get-in-touch').height();
		var hIconClick = $('.icon-click').height();
		//alert(hGetInTouch);
		if($('.icon-click').hasClass('show')) {
			$('.moduletable.get-in-touch').animate({
				bottom: -hGetInTouch + hIconClick,
			},400);
			$('.icon-click').removeClass('show');
		} else {
			$('.icon-click').addClass('show');
			$('.moduletable.get-in-touch').animate({
				bottom: 0,
			},400);
		}
	});	

});
function isIE () {
	var myNav = navigator.userAgent.toLowerCase();
	return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}
})(jQuery);