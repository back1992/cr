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
 
defined('_JEXEC') or die;

//require_once JPATH_COMPONENT.'/controller.php';


class Bt_SocialconnectControllerSocialconnect extends JControllerLegacy
{
	/*
	* Create link to connect social 
	*/
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
	public function update(){
		
		$db = JFactory::getDbo();
		$jinput = JFactory::getApplication()->input;
		$messageId =$jinput->get('id', '', 'INT');
		$url =$jinput->get('url', '', 'STRING');
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']){
			$update_time =JFactory::getDate()->toSql();
			$query='UPDATE  #__bt_messages  SET	event= event + 1 WHERE id ='.$messageId;
			$db->setQuery($query);
			$db->query();
		}
		$this->setRedirect(JRoute::_($url, false));	
	}

	
}
