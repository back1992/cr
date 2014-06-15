jQuery.noConflict();
if(typeof(BTLJ)=='undefined') var BTLJ = jQuery;
if(typeof(btTimeOut)=='undefined') var btTimeOut;
if(typeof(btlOpt)=='undefined') var btlOpt='';
if(typeof(requireRemove)=='undefined') var requireRemove = true;

var autoPos = true;


BTLJ(document).ready(function() {
	
	BTLJ('#btl-content').appendTo('body');
	BTLJ(".btl-input #jform_profile_aboutme").attr("cols",21);
	BTLJ('.bt-scroll .btl-buttonsubmit').click(function(){		
		setTimeout(function(){
			if(BTLJ("#btl-registration-error").is(':visible')){
				BTLJ('.bt-scroll').data('jsp').scrollToY(0,true);
			}else{
				var position = BTLJ('.bt-scroll').find('.invalid:first').position();
				if(position) BTLJ('.bt-scroll').data('jsp').scrollToY(position.top-15,true);
			}
		},20);
	})
		
	jQuery('#btl-content-registration .bt-scroll').css({'max-width':btlOpt.RB_SIZE+'px','max-height':'100%'});
	//SET POSITION
	if(BTLJ('.btl-dropdown').length){	
		setFPosition();
		BTLJ(window).resize(function(){
			setFPosition();
		})
	}
	
	BTLJ(btlOpt.LOGIN_TAGS).addClass("btl-modal");
	if(btlOpt.REGISTER_TAGS != ''){
		BTLJ(btlOpt.REGISTER_TAGS).addClass("btl-modal");
	}

	// Login event
	var elements = '#btl-panel-login';
	if (btlOpt.LOGIN_TAGS) elements += ', ' + btlOpt.LOGIN_TAGS;
	if (btlOpt.MOUSE_EVENT =='click'){ 
		BTLJ(elements).click(function (event) {
				showLoginForm();
				event.preventDefault();
		});	
	}else{
		BTLJ(elements).hover(function () {
				BTLJ(this).trigger('click',	showLoginForm());
		},function(){});
	}

	// Registration/Profile event
	elements = '#btl-panel-registration';
	if (btlOpt.REGISTER_TAGS) elements += ', ' + btlOpt.REGISTER_TAGS;
	if (btlOpt.MOUSE_EVENT =='click'){ 
		BTLJ(elements).click(function (event) {
			showRegistrationForm();
			event.preventDefault();
		});	
		BTLJ("#btl-panel-profile").click(function(event){
			showProfile();
			event.preventDefault();
		});
	}else{
		BTLJ(elements).hover(function () {
				if(!BTLJ("#btl-integrated").length){
					BTLJ(this).trigger('click',	showRegistrationForm());
				}
		},function(){});
		BTLJ("#btl-panel-profile").hover(function () {
				showProfile();
		},function(){});
	}
	BTLJ('#register-link a').click(function (event) {
			if(BTLJ('.btl-modal').length){
				BTLJ.fancybox.close();setTimeout("showRegistrationForm();",1000);
			}
			else{
				showRegistrationForm();
			}
			event.preventDefault();
	});	
	
	// Close form
	BTLJ(document).click(function(event){
		if(requireRemove && event.which == 1) btTimeOut = setTimeout('BTLJ("#btl-content > div").slideUp();BTLJ(".btl-panel span").removeClass("active");',10);
		requireRemove =true;
	})
	BTLJ(".btl-content-block").click(function(){requireRemove =false;});	
	BTLJ(".btl-panel span").click(function(){requireRemove =false;});	
	
	// Modify iframe
	BTLJ('#btl-iframe').load(function (){
		//edit action form	
		oldAction=BTLJ('#btl-iframe').contents().find('form').attr("action");
		if(oldAction!=null){
			if(oldAction.search("tmpl=component")==-1){
				if(BTLJ('#btl-iframe').contents().find('form').attr("action").indexOf('?')!=-1){	
					BTLJ('#btl-iframe').contents().find('form').attr("action",oldAction+"&tmpl=component");
				}
				else{
					BTLJ('#btl-iframe').contents().find('form').attr("action",oldAction+"?tmpl=component");
				}
			}
		}
	});	
	
	BTLJ('.social_btlogin').find('img').bind({
		mouseenter: function() {
			BTLJ(this).addClass('buttonhover');
		},
		mouseleave:function(){
			BTLJ(this).removeClass('buttonhover');
		}
	});
	
});

function setFPosition(){
	if(btlOpt.ALIGN == "center"){
		BTLJ("#btl-content > div").each(function(){
			var panelid = "#"+this.id.replace("content","panel");
			var left ='';
			if (typeof (BTLJ(panelid).offset()) !='undefined'){	
				var left = BTLJ(panelid).offset().left - BTLJ(this).width();	
			}
			if(left < 0) left = 0;
			BTLJ(this).css('left',left);
		});
	}else{
		if(btlOpt.ALIGN == "right"){
			BTLJ("#btl-content > div").css('right',BTLJ(document).width()-BTLJ('.btl-panel').offset().left-BTLJ('.btl-panel').width());
		}else{
			BTLJ("#btl-content > div").css('left',BTLJ('.btl-panel').offset().left);
		}
	}	
	BTLJ("#btl-content > div").css('top',BTLJ(".btl-panel").offset().top+BTLJ(".btl-panel").height()+2);	
}

// SHOW LOGIN FORM
function showLoginForm(){
	BTLJ('.btl-panel span').removeClass("active");
	var el = '#btl-panel-login';
	BTLJ.fancybox.close();
	if(BTLJ(el).hasClass("btl-modal")){
		BTLJ(el).addClass("active");
		BTLJ("#btl-content > div").fadeOut();
		BTLJ(el).fancybox({
			padding             : 0,
			maxWidth			:btlOpt.LB_SIZE,
			maxHeight			:'100%',
			openEffect			: 'fade',
			closeEffect			: 'fade',
			openSpeed			:'slow',
			autoResize 			:true,
			autoCenter 			:true,			
			closeBtn 			:true,
			overlayShow         : true,
			scrolling 			:'auto',
			afterShow	:	function() {
				
			},
			beforeShow  : function() {
			
			}
		});				 
	}
	else
	{	
		BTLJ("#btl-content > div").each(function(){
			if(this.id=="btl-content-login")
			{
				if(BTLJ(this).is(":hidden")){
					BTLJ(el).addClass("active");
					BTLJ(this).slideDown();
					}
				else{
					BTLJ(this).fadeOut();
					BTLJ(el).removeClass("active");
				}						
					
			}
			else{
				if(BTLJ(this).is(":visible")){						
					BTLJ(this).fadeOut();
					BTLJ('#btl-panel-registration').removeClass("active");
				}
			}
			
		})
	}
}
	BTLJ( window ).resize(function() {
	var widthWindow = window.innerWidth;
		if(widthWindow <= 480) {
			BTLJ(".bt-scroll").addClass("moblile");
		} else {
			BTLJ(".bt-scroll").removeClass("moblile");
		}
	});

// SHOW REGISTRATION FORM
function showRegistrationForm(){
	if(BTLJ("#btl-integrated").length){
		window.location.href=BTLJ("#btl-integrated").val();
		return;
	}
	BTLJ('.btl-panel span').removeClass("active");
	BTLJ.fancybox.close();
	var el = '#btl-panel-registration';
	var containerWidth = 0;
	var containerHeight = 0;
	var widthWindow = window.innerWidth;
	if(widthWindow <= 480) {
		BTLJ(".bt-scroll").addClass("moblile");
	} else {
		BTLJ(".bt-scroll").removeClass("moblile");
	}
	
	if(BTLJ(el).hasClass("btl-modal")){
		
		//SET CSS FOR CALENDAR
		BTLJ('.calendar').each(function(){
			BTLJ(this).click(function(){				
				BTLJ("div.calendar:last").show().css({'position':'absolute','z-index':9999});
				BTLJ(".calendar").click(function(){requireRemove =false;});	
			});
		});
		//SET CSS FOR CALENDAR JOOMLA 3.0
		BTLJ('.input-append .btn').each(function(){
			BTLJ(this).click(function(){		
			BTLJ("div.calendar:last").show().css({'position':'fixed','z-index':9999});
			});
		});
		BTLJ(el).addClass("active");
		BTLJ("#btl-content > div").slideUp();
		BTLJ(el).fancybox({
			padding             : 0,
			maxWidth			: btlOpt.RB_SIZE,
			maxHeight			:'100%',
			openEffect			: 'fade',
			closeEffect			: 'fade',
			openSpeed			:'slow',
			autoResize 			:true,
			autoCenter 			:true,			
			closeBtn 			:true,
			scrolling 			:'auto',
			overlayShow         :true,
			afterShow	:	function() {
				//BTLJ('.btl-content-block').jScrollPane();	
			},
			beforeShow  : function() {
				
			}
		});
		
	}
	else
	{		
		
		//SET CSS FOR CALENDAR
		BTLJ('.calendar').each(function(){
			BTLJ(this).click(function(){				
				BTLJ("div.calendar:last").show().css({'position':'absolute','z-index':9999});
				BTLJ(".calendar").click(function(){requireRemove =false;});	
			});
		});
		
		BTLJ("#btl-content > div").each(function(){
			if(this.id=="btl-content-registration")
			{
				if(BTLJ(this).is(":hidden")){
					BTLJ(el).addClass("active");
					BTLJ(this).slideDown();
					
					}
				else{
					BTLJ(this).slideUp();								
					BTLJ(el).removeClass("active");
					}
			}
			else{
				if(BTLJ(this).is(":visible")){						
					BTLJ(this).slideUp();
					BTLJ('#btl-panel-login').removeClass("active");
				}
			}
			
		})
	}
}

// SHOW PROFILE (LOGGED MODULES)
function showProfile(){
	var el = '#btl-panel-profile';
	BTLJ("#btl-content > div").each(function(){
		if(this.id=="btl-content-profile")
		{
			if(BTLJ(this).is(":hidden")){
				BTLJ(el).addClass("active");
				BTLJ(this).slideDown();
				}
			else{
				BTLJ(this).slideUp();	
				BTLJ('.btl-panel span').removeClass("active");
			}				
		}
		else{
			if(BTLJ(this).is(":visible")){						
				BTLJ(this).slideUp();
				BTLJ('.btl-panel span').removeClass("active");	
			}
		}
		
	})
}
function registerAjax(){
	BTLJ("#btl-formregistration").submit(function() {
		BTLJ.ajax({
		 type: "POST",
		 beforeSend:function(){
			   BTLJ("#btl-register-in-process").show();			   
		   },
		  url: btlOpt.BT_AJAX,
		  data: BTLJ(this),
		  success: function() {
				BTLJ(".btl-formregistration").children("div").hide();
				BTLJ("#btl-success").html(html);	
				BTLJ("#btl-success").show();	
				setTimeout(function() {window.location.reload();},7000);
		   }
		});
	});
}

// AJAX LOGIN
function loginAjax(){
	if(BTLJ("#btl-input-username").val()=="") {
		showLoginError(Joomla.JText._('REQUIRED_USERNAME'));
		return false;
	}
	if(BTLJ("#btl-input-password").val()==""){
		showLoginError(Joomla.JText._('REQUIRED_PASSWORD'));
		return false;
	}
	var token = BTLJ('.btl-buttonsubmit input:last').attr("name");
	var value_token = encodeURIComponent(BTLJ('.btl-buttonsubmit input:last').val()); 
	var datasubmit= "bttask=login&username="+encodeURIComponent(BTLJ("#btl-input-username").val())
	+"&passwd=" + encodeURIComponent(BTLJ("#btl-input-password").val())
	+ "&"+token+"="+value_token
	+"&return="+ encodeURIComponent(BTLJ("#btl-return").val());
	
	if(BTLJ("#btl-checkbox-remember").is(":checked")){
		datasubmit += '&remember=yes';
	}
	
	BTLJ.ajax({
	   type: "POST",
	   beforeSend:function(){
		   BTLJ("#btl-login-in-process").show();
		   BTLJ("#btl-login-in-process").css('height',BTLJ('#btl-content-login').outerHeight()+'px');
		   
	   },
	   url: btlOpt.BT_AJAX,
	   data: datasubmit,
	   success: function (html, textstatus, xhrReq){
		  if(html == "1" || html == 1){
			   window.location.href=btlOpt.BT_RETURN;
		   }else{
			   if(html.indexOf('</head>')==-1){		   
				   showLoginError(Joomla.JText._('E_LOGIN_AUTHENTICATE'));
				}
				else
				{
					if(html.indexOf('btl-panel-profile')==-1){ 
						showLoginError('Another plugin has redirected the page on login, Please check your plugins system');
					}
					else
					{
						window.location.href=btlOpt.BT_RETURN;
					}
				}
		   }
	   },
	   error: function (XMLHttpRequest, textStatus, errorThrown) {
			alert(textStatus + ': Ajax request failed!');
	   }
	});
	return false;
}
function showLoginError(notice,reload){
	BTLJ("#btl-login-in-process").hide();
	BTLJ("#btl-login-error").html(notice);
	BTLJ("#btl-login-error").show();
	if(reload){
		setTimeout(function() {window.location.reload();},5000);
	}
}