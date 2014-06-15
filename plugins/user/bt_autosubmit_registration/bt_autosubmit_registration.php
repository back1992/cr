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
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.helper');
require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/socialpost.php');	
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/btpublishes.php');
class plgUserBt_autosubmit_registration extends JPlugin
{	

	function onUserAfterSave($user, $isnew, $success, $msg)
	{
		if(!$isnew) return;
		$user=(object) $user;			
		$db = JFactory::getDbo();
		//Replace message
			$uri = JURI::getInstance();
			$link = $uri->getScheme() . "://" . $uri->getHost();
			$status = $this->params->def('wellcome','');
			$pattern = '{name}';								
			$status = str_replace($pattern, $user->name,$status);
			$pattern = '{site_url}';							
			$status = str_replace($pattern, $link , $status);							
			$message =$status;
		
		//Description website
		$description =self::getdataWeb($link);
			$infomation ='';						
			if(isset($description['description'])){
				$infomation = $description['description'];
			}else{
				if(isset($description['title'])){
					$infomation = $description['title'];
				}else{
				$infomation = $link;
				}
			}
		//Link 
			$link =JHtmlBt_Socialpost::getcaseshortlink($link);
			$created_time = JFactory::getDate()->toSql();			
		
			$trigger ='User Register';
			$title = 'Welcome '.$user->name;
			$description = $infomation;
			$picture =$this->params->def('logourl','');
		if($user->enabled_publishing ==1){
			if(isset($user->socialId) && $user->socialId!=''){					
				switch($user->loginType){
						case 'facebook':															
								$facebook_id= $user->socialId;								
								$url = "https://graph.facebook.com/$facebook_id/feed";	
								$token = $user->access_token;								
								$attachment =self::setAttachment($token,$message,$title,$link,$description,$picture);
								$attachment = $attachment['facebook'];
								 //Post message user
								$return =JHtmlBt_Socialpost::socialPost($user->loginType,$attachment, $url ,$user->name,$user->socialId);					 
								$log = $return['log'];
								$publish = $return['publish'];					
								$socialtype="facebookprofile";							 
								break; 
						
						case 'twitter':	
						
							if(!class_exists('TwitterOAuth')){
									require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/twitter/twitteroauth.php');	
							}							
							$session = JFactory::getSession();
							$connection = unserialize($session->get('connection'));													
							$attachment =self::setAttachment('',$message,'','','','');
							$attachment =$attachment['twitter'];
							$parameters = array('status' => $message);
							$updatestatus = $connection->post('statuses/update', $parameters);
							if(isset($updatestatus->errors)){
								$log = $updatestatus->errors[0]->message;
								$publish =0;
							}else{
								$log = JTEXT::_('Message has sent '.'<a href="https://twitter.com/'.$user->username.'" style="text-decoration: underline;" target="_blank">'.$user->name.'</a>'.' successfully');
								$publish=1;
							}
							$socialtype="twitterprofile";
								
							break;
						
						case 'linkedin':
							$token='';
							$attachment =self::setAttachment($token,$message,$title,$link,$description,$picture);
							$attachment =$attachment['linkedin'];
						
							$response = JHtmlBt_Socialpost::fetch($user->access_token,$attachment);
							$return = JHtmlBt_Socialpost::socialPost($user->loginType,$response['body'],$response['url'],$user->name,$user->socialId); 
							$log = $return['log'];
								$publish = $return['publish'];							
								$socialtype="linkedinprofile";							
							break ;
							
						case 'google':
							$attachment ='';
							$socialtype = 'googleprofile';
							$publish = 1;
							$log ='';
							break; 
							
						}		
						$newArray =array();
						$newArray['item']->social_id = $user->socialId;
						$newArray['item']->access_token = $user->access_token;
						$newArray['attachment'] = $attachment;
						$message_type='profile';
						$seralizedCron = self::setDataOfline($user->loginType,$user->name,$newArray);	
						$query='INSERT INTO  #__bt_messages (`createdby`,`type`,`title`,`message`,`trigger`,`published`,`log`,`event`,`scron`,`sent_time`,`created_time`,`chanel_id`,`message_type`) VALUES (\'' . (Jtext::_('System')). '\',\''.$socialtype.'\',\'' . $message . '\',\'' .JHtmlBt_Socialpost::processmessage($attachment) . '\',\'' . $trigger . '\',\'' . $publish . '\',\'' . $log . '\',\''.'0'.'\',\'' .$seralizedCron. '\',\''.$created_time.'\', \''.$created_time.'\',\''.$user->id.'\',\''.$message_type.'\')';
						$db->setQuery($query);
						$db->query();
						
				}
			}
			
			
	}	
		public function setAttachment($token,$message,$title,$link,$description,$picture){
		
		$attachment =  array(
			'facebook' =>array(
							'access_token' => $token,
							'message' => $message,
							'name' => $title,
							'link' => $link,
							'description' => $description,
							'picture'=>$picture,	
							),
			'linkedin'=>array(
							'comment' => $message,
							'content' => array(
								'title' => $title,
								'description' => $description,
								'submittedUrl' => $link,
								'submitted-image-url'=>$picture
							),
							'visibility' => array('code' => 'anyone' )
							),
			'twitter' =>array (
								'message' => $message
							),
			'default' =>array(												
							'message' => $message,
							'name' => $title,
							'link' => $link,
							'description' => $description,
							'picture'=>$picture,	
							)
		);	
		return $attachment;
	
	}
	
	public function setDataOfline($alias,$name,$attachment){
		$cron = array();		
		$cron['alias'] = $alias;
		$cron['name'] = $name;															
		$cron['attachment'] = $attachment;		
		return serialize($cron);
	
	}
	
	static function getdataWeb($submitted){	
		$info = array();
		$urlContents = file_get_contents($submitted);
		$dom = new DOMDocument();
		@$dom->loadHTML($urlContents);
		
		$titly = $dom->getElementsByTagName('title');
		$title= $titly->item(0)->nodeValue;
		$info['title']= $title;
		$metatagarray = get_meta_tags($submitted);	
		
		if(!empty($metatagarray)){
			$description = isset($metatagarray["description"]) ? $metatagarray["description"] :'';
			$info['description']= $description;
		}
		return $info;
	 }
}

?>