<?php
/**
 * @package 	bt_socialconnect - BT Social Connect Component
 * @version		1.0.0
 * @created		October 2013
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2013 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;
require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/socialpost.php');	
require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/factory.php');	
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/btpublishes.php');

class Bt_SocialconnectHelperAutoPost
{
	
	public static function postArticle($article,$cronjob,$config){		
	
		$app = JApplication::getInstance('site');
		$router =  $app->getRouter();
		$params = JComponentHelper::getParams('com_bt_socialconnect');				
				
			$uri = JURI::getInstance();
			$user = JFactory::getUser();			
			$db = JFactory::getDbo();
			// Get link article
			$link_article =$article->link;
			$self_url = $router->build($link_article);
			$link_article = $self_url->toString();
			$link_article = str_replace('/administrator/', '/', $link_article);
			$link_article = $uri->getScheme() . "://" . $uri->getHost() . $link_article;	
			
			$trigger =$article->trigger;
			$template =$config->def('template', '');
			$dc_template =$config->def('des_template', '');
			
			$image ='';	
			$default_image = $config->def('default_img', '');
			if($default_image  !=''){
				$image = $default_image;
			}else{
				$image =$article->image;
			}
			if($image !=''){
				if (strpos($image, "http")!==false){
					$urlimage = $image;
				}else{
					$urlimage =JURI::root().$image;
				}
			}else{
				$urlimage='';
			}				
			
			// Crop image neu config trong admin
			if($params->get('cropthumb')){
				$saveDir = JPATH_SITE .'/images/bt_socialconnect/';	
				JHtmlBt_Socialpost::createFolder($saveDir);		
				$images_path = $saveDir .'thumb/';	
				JHtmlBt_Socialpost::createFolder($images_path);				
				if($urlimage){										
						$cropimage =JHtmlBt_Socialpost::cropimage($urlimage,$images_path,$params);
						$urlimage = JURI::root().'images/bt_socialconnect/thumb/'.$cropimage;
				}
			
			}
			
			//Prepare content----------------------------------------------------- post			
			$created_time = JFactory::getDate()->toSql();
			$message =self::setmessage($template,$user->name,$article,$link_article);
			$description = self::setmessage($dc_template,$user->name,$article,$link_article);
			$title = $article->title;
			$link = JHtmlBt_Socialpost::getcaseshortlink($link_article);
			$picture =$urlimage;
			$default_content= self::setDataSocial('',$message,$title,$link,$description,$picture);
			$publish = 2;		
			$log = 'Waiting to process in queue';
			
			// Post len social network neu tai khoan ket noi nhieu mang xa hoi
			$items = JHtmlBt_Socialpost::getSocialpostuser($user->id);	
			foreach ($items AS $key =>$item){
			$token= $item->access_token;
			switch ($item->social_type){
				case 'facebook':																	
						$facebook_id= $item->social_id;								
						$url = "https://graph.facebook.com/$facebook_id/feed";													
						$sociatype ='facebookprofile';	
						$message_type='profile';																	
						
						//Check count the link and get id Re set attachmetn when save attachment default
						$urlprocess =self::processAttachment($user->name,$sociatype,$article,$link_article,$default_content,$trigger,$created_time,$params,$template,$message,$link,$user->id,$message_type,$db);							
						$attachment = self::setAttachment($token,$urlprocess['message'],$title,$urlprocess['link'],$description,$picture);
						$attachment = $attachment['facebook'];
						if(!$cronjob){
						//Post message user connect and update																	
							$result =JHtmlBt_Socialpost::socialPost($item->social_type,$attachment, $url ,$user->name,$facebook_id);				 
							$log = $result['log'];
							$publish = $result['publish'];
						}
						
						break;											
				case 'twitter':						
						$sociatype ='twitterprofile';
						$message_type='profile';
						$twitter =unserialize($item->access_token);							
						
						if(!class_exists('TwitterOAuth')){
								require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/twitter/twitteroauth.php');	
						}				
						$connection = new TwitterOAuth($params->get('ttappId',''), $params->get('ttsecret',''), $twitter['oauth_token'], $twitter['oauth_token_secret']);
												
						$urlprocess =self::processAttachment($user->name,$sociatype,$article,$link_article,$default_content,$trigger,$created_time,$params,$template,$message,$link,$user->id,$message_type,$db);
						$attachment = $urlprocess['message'];						
						//Share link in twitter
						if(!$cronjob){
							$parameters = array('status' => $urlprocess['message']);
							$status = $connection->post('statuses/update', $parameters);
								if(isset($status->errors)){
									$log =$status->errors[0]->message;
									$publish =0;
								}else{
									$log=JTEXT::_('Message has sent '.'<a href="https://twitter.com/'.$user->username.'" style="text-decoration: underline;" target="_blank">'.$user->name.'</a>'.' successfully');
									$publish =1;
								}											
						}
						break;
				case 'linkedin':
				
						$sociatype ='linkedinprofile';
						$message_type='profile';									
												
						$urlprocess =self::processAttachment($user->name,$sociatype,$article,$link_article,$default_content,$trigger,$created_time,$params,$template,$message,$link,$user->id,$message_type,$db);
						//Set re attachment
						$attachment =self::setAttachment($token,$urlprocess['message'],$title,$urlprocess['link'],$description,$picture);
						$attachment = $attachment['linkedin'];
						if(!$cronjob){
							$response = JHtmlBt_Socialpost::fetch($item->access_token,$attachment);								
							
							$return =JHtmlBt_Socialpost::socialPost($item->social_type,$response['body'], $response['url'] ,$user->name,$item->social_id);
							$log = $return['log'];
							$publish = $return['publish'];
						}
						
						break;
				}
				//Set Cronjob for all site to renew post	
				$data =array();
				$data['item'] = $item;
				$data['attachment'] = $attachment;
				//Set data to renew post
				$seralizedCron = self::setDataOfline($item->social_type,$user->name,$data);				
				$update_time =JFactory::getDate()->toSql();
				$query='UPDATE  #__bt_messages  SET	`published`= \''.$publish.'\',`log` = \''.$log.'\',`scron`= \''.$db->escape($seralizedCron).'\',`sent_time`= \''.$update_time.'\' WHERE `id` ='.(int)$urlprocess['id'];
				$db->setQuery($query);
				$db->query();
			}
			
			//Post message group system
			
				$params_scoialpublish =$config->get('channel',0);		
				$result =JHtmlBt_Socialpost::getpublish($params_scoialpublish);						
				
				if(!empty($result)){									
					foreach ($result AS $key=>$item){						
						$publishes = new BTPublishes($item->alias,$db);								
						$name =  $publishes->type;
						$access_token = $publishes->params->get('access_token');
						//Check facebookname post message
						if($publishes->params->get('uname')){
							$uname = $publishes->params->get('uname');							
						}else{
							$uname = $user->name;
						}
						//Check facebook id
						$uid = $publishes->params->get('uid');
						$getuser = JHtmlBt_Socialpost::getUser($uid);
						if(empty($getuser)){
							$userpost = $user->name;
						}else{
							$userpost =	$getuser;
						}						
											
						//Set and assign content to post
							$token = $access_token;
							$message = self::setmessage($template,$uname,$article,$link_article);
							$title = $article->title;
							$message_type='system';
						//end 							
						//Set attachment default 
																	
						
						$urlprocess =self::processAttachment($userpost,$item->type,$article,$link_article,$default_content,$trigger,$created_time,$params,$template,$message,$link,$item->id,$message_type,$db);								
						$attachment =self::setDataSocial($token,$urlprocess['message'],$title,$urlprocess['link'],$description,$picture);						
						$seralizedCron = self::setDataOfline($item->alias,$name,$attachment);
						//Kiem tra co de dang cronjob ko
						if($cronjob){							
							$time =  array();		
							$time['hour'] = $params->get('schedule','');
							$time['date']='';
							$schedule = serialize($time);
							$query='UPDATE  #__bt_messages  SET	 `scron`= \''.$db->escape($seralizedCron).'\',`schedule` = \''.$db->escape($schedule).'\' WHERE id ='.(int)$urlprocess['id'];
							$db->setQuery($query);
							$db->query();
						
						}else{	
							if(!empty($name)){
								$result = $name::postMessage($publishes,$attachment);
								if(!$db->connected()){
									$db = BTJFactory::createDbo();
								}
								if($result['checked'] == 1){									
									$update_time =JFactory::getDate()->toSql();
									$query='UPDATE  #__bt_messages  SET	`published`= \''.$result['publish'].'\',`log` = \''.$result['log'].'\',`scron`= \''.$db->escape($seralizedCron).'\',`sent_time`= \''.$update_time.'\' WHERE `id` ='.(int)$urlprocess['id'];
									$db->setQuery($query);
									$db->query();
								}
							}
						}
					}
					
				}	
			//End	
				
	}	
	
	
	
	public static function setDataSocial($token,$message,$title,$link,$description,$picture){
		$data = array(
						'access_token' => $token,
						'message' => $message,
						'name' => $title,
						'link' => $link,
						'description' => $description,
						'picture'=>$picture,	
						);
		return $data;
	
	}
	
	public static function setDataOfline($alias,$name,$attachment){
		$cron = array();		
		$cron['alias'] = $alias;
		$cron['name'] = $name;															
		$cron['attachment'] = $attachment;		
		return serialize($cron);
	
	}
	
	public static function setAttachment($token,$message,$title,$link,$description,$picture){
		
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
							)			
		);	
		return $attachment;
	
	}
	
	public static function processAttachment($name,$sociatype,$article,$link_article,$attachment,$trigger,$created_time,$params,$template,$message,$link,$chanel_id,$message_type,$db){
		
		$newArray =array();		
		$publish = 2;		
		$log = 'Waiting to process in queue';		
		$query='INSERT INTO  #__bt_messages (`createdby`,`type`,`title`,`url`,`message`,`trigger`,`published`,`log`,`event`,`created_time`,`params`,`chanel_id`,`message_type`) VALUES(\'' .$db->escape($name) . '\',\''.$sociatype.'\',\'' . $db->escape($article->title) . '\',\'' . $db->escape($link_article) . '\',\'' .$db->escape(JHtmlBt_Socialpost::processmessage($attachment)) . '\', \''.$trigger.'\', \''.$publish.'\', \''.$log.'\',\''.'0'.'\',\''.$created_time.'\',\''.$article->params.'\',\''.$chanel_id.'\',\''.$message_type.'\')';
		$db->setQuery($query);	
		$db->query();
		$id = $db->insertid();				
		//Setlink 
		$count_post =$params->get('count_post',1);
			//Set link	
			if($count_post == 1){
				$relink = JURI::root().'index.php?option=com_bt_socialconnect&task=socialconnect.update&id='.$id.'&url='.$link;								
				$link_redirect = JHtmlBt_Socialpost::getcaseshortlink($relink);
				$message = self::setmessage($template,$name,$article,$relink);
			}else{
				$link_redirect  = $link;									
			}							
		$newArray['link'] =$link_redirect;
		$newArray['message'] =$message;
		$newArray ['id'] =$id;
		return $newArray;
		
	}
	
	public static function setmessage($params,$author,$article,$link){
		
		$message= $params; 
		$pattern = '{title}';
		$message = str_replace($pattern, $article->title,$message);
		$pattern = '{shorturl}';
		$message = str_replace($pattern, JHtmlBt_Socialpost::getcaseshortlink($link),$message);
		$pattern = '{url}';
		$message = str_replace($pattern, $link,$message);
		$pattern = '{authorname}';
		$message = str_replace($pattern, $author,$message);		
		$regex = '/\{introtext\:(.*)\}/Ui';	
		preg_match_all($regex,$message,$matches);			
		if(!empty($matches[1])){
			JFilterOutput::cleanText($article->introtext);
			$article->introtext = str_replace("\n"," ",$article->introtext);
			if((int)$matches[1][0] == 0){			
				$small =$article->introtext;
			}
			else{
				if(function_exists('mb_substr')){
					$small = mb_substr($article->introtext, 0,(int)$matches[1][0]);
				}
				else{
					$small = mb_substr($article->introtext, 0,(int)$matches[1][0]);
				}
			}
			$pattern='{introtext:'.(int)$matches[1][0].'}';
			$message =str_replace($pattern, $small,$message);	
		
		}
		$pattern = '{fulltext}';
		$message = str_replace($pattern, $article->fulltext,$message);	
		return $message;	
	}
	
}
