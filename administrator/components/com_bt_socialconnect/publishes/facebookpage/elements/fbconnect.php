<?php
/**
 * @package 	bt_socialconnect - BT Social Connect Component
 * @version		1.0.0
 * @created		February 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2014 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

class JFormFieldFbconnect  extends JFormFieldList
{
	protected $type = 'fbconnect';	 
	
	function getInput()
	{
		
		$access_token='';
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		$appid= $params->get('fbappId');
		$client_secret= $params->get('fbsecret');
		$user_id='';
		$html 	= '';
		$html .='
		<style>
		.btnload{
			background-color: #FFB004 !important;
			background-image: -moz-linear-gradient(center bottom , #FF9103 0%, #FFB004 100%) !important;
			border: 0.2px solid #FF9103 !important;
			color: #FFFFFF;
			cursor: pointer;
			display: inline;
			height: 25px;
			line-height: 25px;
			margin-left: 5px;		
		}
		</style>';
		
		$uri = JFactory::getURI ();
		$uri->delVar( 'code');
		$linkRedirect = $uri->toString ();		
		$html .='<div id="btss-loading" style="display:none"></div>';	
		
		$html .="
		<script>	
			function newPopup(pageURL, title,w,h){
				var left = (screen.width/2)-(w/2);
				var top = (screen.height/2)-(h/2);
				var popupWindow = window.open(pageURL,'connectingPopup','height='+h+',width='+w+',left='+left+',top='+top+',resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes');
				
			}
			
			function getlink(){			
				var apid ='".$appid."';								
				var client_secret ='".$client_secret."';
				var link='';				
				if(apid.trim().length  != 0 && client_secret.trim().length !=0){
					link =\"https://www.facebook.com/dialog/oauth?client_id=\" + apid + \"&redirect_uri=\" + encodeURIComponent (window.location+'&tmpl=component') + \"&scope=offline_access,read_stream,publish_stream,manage_pages\";
				}else{
					link='".JURI::root()."index.php?option=com_bt_socialconnect&view=connect&tmpl=component';
				}
				return link;
			} 			
		</script>";		
		
		$code = JRequest::getString('code');
		if($code){	     
		
		$html .='<script>				
				var code="'.$code.'";				
				var appid ="'.$appid.'";			
				var client_secret ="'.$client_secret.'";			
				jQuery(".contentpane").css("display","none");
				jQuery("#channel-form").css("display","none");
				jQuery.ajax({
					url: location.href,
					data: "client_id="+appid+"&client_secret=" +client_secret+"&redirect_uri="+encodeURIComponent(\''.$linkRedirect.'\')+"&code="+code+"&method=gettoken" ,
					type: "post",
					beforeSend: function(){
							jQuery(".contentpane").show().append(jQuery("#btss-loading").show());
					},					
					success: function(response){						
						var data = jQuery.parseJSON(response);
						var access_token =data.access_token;						
						jQuery("#jform_params_app_appid",window.opener.document).val(appid);
						jQuery("#jform_params_access_token",window.opener.document).val(access_token);										
						jQuery.ajax({
							url: location.href,
							data: "access_token="+access_token+"&method=getuser" ,
							type: "post",
							success: function(response){
								var data = jQuery.parseJSON(response);								
								jQuery("#jform_params_uid",window.opener.document).val(data.uid);
								jQuery("#jform_params_uname",window.opener.document).val(data.uname);
								window.opener.document.getElementById(\'groups\').innerHTML=(data.data);								
								window.close();
							}
						});				
							
					}
				});
			</script>';			
		}				
		if(!empty($this->value)){		
			
			$db 	= JFactory::getDBO();
			$id 	= JRequest::getVar('id');			
			$param_text = '';
			if ($id) {
				$sql 	= "SELECT params FROM `#__bt_channels`
							WHERE `id`=$id LIMIT 1";
				$db->setQuery($sql);
				$param_text 	= $db->loadResult();
			}
			
			$data= json_decode($param_text);				
			
			$html .="<div id=\"groups\">";
			$html .='<div style="height:50px;"><a href="https://www.facebook.com/'.$data->uid.'" target="_blank"><img style="width:50px; height:50px;float:left;margin:5px" src="http://graph.facebook.com/'.$data->uid.'/picture"></a><br/>';			
			$html .='<a href="https://www.facebook.com/'.$data->uid.'" target="_blank">'.$data->uname.'</a></div>';
			$html .='<br/>';			
			foreach($this->value AS $key=>$value){	
			
				$check='';				
				if(isset($value->checked)){
					$check="checked";
				}					
				$html .="<input type='checkbox' ".$check."   value=\"".$value->id."\" name='group[$key][checked] '/> ".'<a href="https://www.facebook.com/'.$value->id.'" target="_blank">'.$value->name."</a>"."<br/>";			
				$html .="<input type='hidden'    value=\"".$value->id."\" name='group[$key][id] '/><br/>";			
				$html .="<input type='hidden'   value=\"".$value->name."\" name='group[$key][name]'/>";
				$html .="<input type='hidden'   value=\"".$value->access_token."\" name='group[$key][access_token]'/>";
				
			}
			$html .="</div>";
		}
		$html .='<div id="groups"></div>';
		$html .= '<input class="btnload"  type="button" value="'.JText::_("PUBLISHED_FACEBOOK_PAGE").'" onclick="JavaScript:newPopup(getlink(),\'Connecting to Facebook ...\',500,450);"/>';
	
		return $html;
	}
}