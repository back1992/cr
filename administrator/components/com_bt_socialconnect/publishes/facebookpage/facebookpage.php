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
 
defined("_JEXEC") or die("Cannot direct access!");

	class FacebookPage{		

			public static function postMessage($params,$data){
				$attachment =array(
							'access_token' => $data['access_token'],
							'message' => $data['message'],
							'name' => $data['name'],
							'link' => $data['link'],
							'description' => $data['description'],
							'picture'=>$data['picture'],	
							);	
				$message =array();
				$fbpages = $params->params->get('groupid');
					$log ='';	
					$publish =1;
					$checked = 0;						
					foreach ($fbpages AS $key=>$pages){
						$id = $pages->id;
						
						//Replace accesstoken to fanpage 
						$attachment['access_token'] = $pages->access_token;
						
						if(isset($pages->checked)){
								$checked = 1;
								$url = "https://graph.facebook.com/$id/feed";											
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL,$url);
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
								curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
								curl_setopt($ch, CURLOPT_POST, true);
								curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
								curl_setopt($ch, CURLOPT_HEADER,0);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
								$go = curl_exec($ch);
								$page = json_decode($go);
								if(isset($page->error)){
									$log .=Jtext::_('PUBLISHED_FACEBOOK_PAGES_SENDMESSAGE').' <a href="https://www.facebook.com/'.$id.'" target="_blank" style="text-decoration: underline;">'.$pages->name.'</a>  - '.$page->error->message.' <br/>' ;	
									$publish =0;
								}else{
									$log .=Jtext::_('PUBLISHED_FACEBOOK_PAGES_SENDMESSAGE').' <a href="https://www.facebook.com/'.$id.'" target="_blank" style="text-decoration: underline;">'.$pages->name.'</a>  - '.JTEXT::_('PUBLISHED_FACEBOOK_PAGES_SENDMESSAGE_SUCCESSFULL').'<br/>';									
									}					
								curl_close ($ch);
						}
					}
				
				$message['log']=$log;
				$message['publish']=$publish;
				$message['checked']=$checked;
				$message['type'] ='facebook';
		return $message;	
			
		}
		
	}

