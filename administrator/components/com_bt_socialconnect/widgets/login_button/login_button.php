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
 
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/btwidget.php');

class Login_button extends BTWidget {	
	
	public static function display($params){
	$buttons = array();
	$buttons = $params->get('buttonlogin','all');
	$html =array();
	
	if(count($buttons) ==1 && $buttons[0] =='all'){
		$html[] =self::showSocialButtons('facebook');
		$html[] =self::showSocialButtons('twitter');
		$html[] =self::showSocialButtons('google');	
		$html[] =self::showSocialButtons('linkedin');	
	}
	else{
		foreach ($buttons AS $key=>$login){
			if($login!='all')
			$html[] =self::showSocialButtons($login);	
		}
	
	}
	return implode(" ",$html);
	}
	
	public static function showSocialButtons($type){
		
		require_once(JPATH_SITE . '/components/com_bt_socialconnect/controllers/socialconnect.php');
		$params = JComponentHelper::getParams('com_bt_socialconnect');			
		switch($type){		
			case'facebook':					
				if($params->get('fbactive')==1){
					
					$fbUr = self ::getlink('facebook');			
					return '<a href="JavaScript:newPopup(\''.$fbUr.'\',\'Connecting to Facebook ...\',600,400);"><div class="btnsc btn-fb"><span class="iconsc icon-fb"></span><span class="icon-border-sc icon-border-fb"></span><span class="text-sc">Facebook</span></div></a>';
				}
				break;
			case'twitter':
				if($params->get('ttactive')==1){					
					$ttUr = self ::getlink('twitter');				
					return '<a href="JavaScript:newPopup(\''.$ttUr.'\',\'Connecting to Twitter ...\',600,400);"><div class="btnsc btn-tt"><span class="iconsc icon-tt"></span><span class="icon-border-sc icon-border-tt"></span><span class="text-sc">Twitter</span></div></a>';
				}				
				break;
			case'google':
			
				if($params->get('ggactive')==1){
				
					$ggUr = self ::getlink('google');				
					return '<a href="JavaScript:newPopup(\''.$ggUr.'\',\'Connecting to Google ...\',600,400);"><div class="btnsc btn-gg"><span class="iconsc icon-gg"></span><span class="icon-border-sc icon-border-gg"></span><span class="text-sc">Google</span></div></a>';
				}
				break;	
			case'linkedin':
				
				if($params->get('linkactive')==1){
				
					$linkedin = self ::getlink('linkedin');				
					return '<a href="JavaScript:newPopup(\''.$linkedin.'\',\'Connecting to linkedin ...\',600,400);"><div class="btnsc btn-in"><span class="iconsc icon-in"></span><span class="icon-border-sc icon-border-in"></span><span class="text-sc">Linkedin</span></div></a>';
				}
				break;				
		
		}
	
	}	
	
	static function getlink($social)
	{	
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		$dialog_url='';
		switch($social){		
		case'facebook':
			$callback_url = JURI::root().'index.php?option=com_bt_socialconnect';
			$app_id =$params->get('fbappId','');
			$fbsecret =$params->get('fbsecret','');
			
			$state ='sc_fb';
			if(trim($app_id)!='' && trim($fbsecret)!=''){
				$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
	       				  . $app_id . "&redirect_uri=" . $callback_url . "&state="
	       				  . $state
	       				  ."&display=popup&scope=email,user_birthday,user_location,email,user_website,user_photos,user_hometown,user_about_me,publish_stream,offline_access";
			}else{
				$dialog_url= JURI::root().'index.php?option=com_bt_socialconnect&view=connect&tmpl=component';
			}
			break;
			
		case'twitter':
			$uri = JFactory::getURI ();
			$uri->setVar ( 'code', 1);
			$uri->setVar ( 'state', 'sc_tt');
			$app_id =$params->get('ttappId','');
			$ttsecret= $params->get('ttsecret');
			
			if(trim($app_id)!='' && trim($ttsecret)!=''  ){
				$dialog_url =  $uri->toString ();
			}else{
				$dialog_url= JURI::root().'index.php?option=com_bt_socialconnect&view=connect&tmpl=component';
			}
			
			break;
			
		case'google':
			$callback_url = urlencode(JURI::base());
			$app_id =$params->get('ggappId','');
			$ggsecret =$params->get('ggsecret','');
			
			$state ='sc_gg';
			if(trim($app_id)!='' && trim($ggsecret)!=''  ){
				$dialog_url = 'https://accounts.google.com/o/oauth2/auth?client_id='
						  .$app_id.'&redirect_uri='.$callback_url.'&state='.$state
						  .'&scope=https://www.googleapis.com/auth/userinfo.email'
						  .'+https://www.googleapis.com/auth/userinfo.profile'
						  .'&response_type=code';
			}else{
				$dialog_url= JURI::root().'index.php?option=com_bt_socialconnect&view=connect&tmpl=component';
			}
		   break;
		   
		 case'linkedin':
			$callback_url = JURI::root().'index.php?option=com_bt_socialconnect';
			$app_id =$params->get('linkappId','');
			$linksecret =$params->get('linksecret','');
			$state ='sc_linkedin';
			$scope='r_fullprofile+r_emailaddress+r_network+rw_nus';
			if(trim($app_id)!='' && trim($linksecret)!=''  ){
				$dialog_url = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id='
							.$app_id.'&scope='
							.$scope.'&state='
							.$state.'&redirect_uri='
							.$callback_url;
			}else{
				$dialog_url= JURI::root().'index.php?option=com_bt_socialconnect&view=connect&tmpl=component';
			}
			break;
		   }
		return $dialog_url;		
       
	}
			
}

?>