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

$path = JPATH_ROOT . '/components/com_bt_socialconnect';
if (!is_dir($path))  return;
require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/social.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/btpublishes.php');	
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/btwidget.php');
	
class plgSystemBt_socialconnect_system extends JPlugin
{
	
	public function onAfterRender()
	{
			
			$app = JFactory::getApplication();	
			if($app->isAdmin()) return;
			$buffer = JResponse::getBody();
			
			$pattern = '{login_btn:facebook}';
			$link = JHtmlBt_Socialconnect::showSocialButtons('facebook');
			$buffer = str_replace($pattern, $link, $buffer);
		
			$pattern = '{login_btn:twitter}';
			$link = JHtmlBt_Socialconnect::showSocialButtons('twitter');
			$buffer = str_replace($pattern, $link, $buffer);
		
			$pattern = '{login_btn:google}';
			$link = JHtmlBt_Socialconnect::showSocialButtons('google');
			$buffer = str_replace($pattern, $link, $buffer);
			
			$pattern = '{login_btn:linkedin}';
			$link = JHtmlBt_Socialconnect::showSocialButtons('linkedin');
			$buffer = str_replace($pattern, $link, $buffer);
			
			$regex = '/\{widget\:(.*)\}/Ui';			
			preg_match_all($regex,$buffer,$matches);			
			
			$checkMultiple = array();			
			
			foreach($matches[1] AS $key => $wIds){
				$wIdArr = 	explode(',', $wIds);
				$content = '';				
				foreach($wIdArr as $walias){
					
					if(in_array($walias,$checkMultiple)){
						continue;
					}
					$widget = new BTWidget($walias);
					$name =  $widget->name;						
					if(!empty($name)){
						$content .= $name::display($widget->params);													
					}	
				}				
				$buffer = str_replace($matches[0][$key], $content, $buffer);
			}
			
			$params = JComponentHelper::getParams('com_bt_socialconnect');			
			$appID= $params->get('fbappId','');
			if($appID!=''){
				$appID	=	'&appId='.$appID.'';
			}
			
			$code = "<div id='fb-root'></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = '//connect.facebook.net/en_GB/all.js#xfbml=1".$appID."';
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>";		
							
			$beforeBody  = "\n".$code."\n</body>";
		
			$buffer      = str_replace("</body>", $beforeBody, $buffer);			
			$google = '<script type="text/javascript">
					  (function() {
					   var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
					   po.src = \'https://apis.google.com/js/client:plusone.js\';
					   var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
					 })();
					</script>';
							
			$insertbfBody  = "\n".$google."\n</body>";
			$buffer      = str_replace("</body>", $insertbfBody, $buffer);
			
			$html ='<script>
				var fbautologin ='.$params->get('fbuserautologin',0).';
				
				function setCookie(c_name,value)
				{			
					var c_value = escape(value); 			
					document.cookie=c_name + "=" + c_value;
				}
					
				 window.fbAsyncInit = function() {
					FB.init({
						appId: "'.$params->get('fbappId','').'",
						status: true, 
						cookie: true, 
						xfbml: true
					});
					setCookie("facebookid","");
						
						FB.Event.subscribe(\'auth.login\', function(response) {
							FB.getLoginStatus(function(response) {			
								if (response.status === \'connected\') {
									if(fbautologin ==1){
										FB.api(\'/me\', function(response) { 						
											setCookie("facebookid",response.id);
											//window.location.reload(); 
										});
									 }
								
								} else if (response.status === \'not_authorized\') {
									console.log(\'not authorized \')
								} else {
									setCookie("facebookid","");
									console.log(\' the user isnnot logged in to Facebook.\');
									
								}
							});
						});
						FB.Event.subscribe(\'auth.logout\', function(response) {
							setCookie("facebookid","");
							window.location.reload(); 
							
						});

				
				}
			
			function logout_button_click(){
		
				var socialid ="'.$this->getSocialID(JFactory::getUser()->id).'";
				if(socialid !=""){
					FB.getLoginStatus(function (response) {	
						if (response.status === \'connected\')
						{
							 FB.api(\'/me\', function(response) { 
								if(socialid == response.id){
									if(fbautologin ==1){
										FB.logout(function (response)
										{
											jQuery(\'#logout-form\').submit();	
										});
									}
									else{
										jQuery(\'#logout-form\').submit();	
									}
								}
								else{
										jQuery(\'#logout-form\').submit();
									}
							})
						}
						else
						{
							jQuery(\'#logout-form\').submit();	
						}
					});
					jQuery(\'#logout-form\').submit();
				}else{				
					jQuery(\'#logout-form\').submit();
				}
			}
		</script>';	
		
		$scripbody  = "\n".$html."\n</body>";		
		$buffer      = str_replace("</body>", $scripbody, $buffer);			
		
		JResponse::setBody($buffer);
		
		
		//Cronjob
		static $cron = null;
		if (!$app->isSite())
		{
			return;
		}			
		$limit =$params->get('limitscron', 5);		
		$dbo = JFactory::getDBO();
		if(is_null($cron)){
		
			$params = JComponentHelper::getParams('com_bt_socialconnect');
			$taskcron = $params->get('taskcron');			
			$token = self::getToken('bt_cronjobcron', 'taskcron', $params);
			
			$cron = false;

			if(!empty($taskcron)){
			
				$jinput = JFactory::getApplication()->input;
				$cron = $jinput->get($token, 0, 'BOOL');
				$task =  $jinput->get('task', 'cron', 'STRING');
				
				if($cron  && $task=='cron'){
							
					$query = 'SELECT * from #__bt_messages WHERE scron != "" AND published =2 LIMIT '.$limit;
					$dbo->setQuery($query);
					$items = $dbo->loadObjectList();
					self::processPost($items,$params);
				}
				
			}
			
		}
		
		return true;
			
     }
	 
	public function onAfterInitialise(){
	
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root()  . 'plugins/system/bt_socialconnect_system/element/css/style.css');
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		 
		if(isset($_REQUEST["code"])){
			$code = $_REQUEST["code"];
		
			if(isset($_REQUEST['state']) && $_REQUEST['state']=='sc_fb'){			
				$callback_url = JURI::root().'index.php?option=com_bt_socialconnect';
				
				$token_url = JHtmlBt_Socialconnect::getTokenUrl($params->get('fbappId',''), $callback_url, $params->get('fbsecret',''), $code);
				
				$response = JHtmlBt_Socialconnect::curlResponse($token_url);
				
				$paramsFB = null;
				parse_str($response, $paramsFB);			
				$graph_url = "https://graph.facebook.com/me?access_token=".$paramsFB['access_token'];
			  
				$user = JHtmlBt_Socialconnect::curlResponse($graph_url);			
				$user = JHtmlBt_Socialconnect::prepareData(json_decode($user),'facebook');			
				$user = JHtmlBt_Socialconnect::assignProfile($user);				
				$user['access_token'] = $paramsFB['access_token'];			
				JHtmlBt_Socialconnect::checkUser($user,$params);	
				if($params->get('fbregister','automatic')=='automatic'){
											
						JHtmlBt_Socialconnect::authenticationSocial($params,$user);			
					
				}
				else{
					if(!empty(JFactory::getUser()->id)){					
						JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
						JHtmlBt_Socialconnect::reloadParent($return_decode);
					}else{
						JHtmlBt_Socialconnect::loadDataForm($user);
					}
				}
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state']=='sc_tt'){
			
				if(!class_exists('TwitterOAuth')){
						require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/twitter/twitteroauth.php');	
				}
				$session = JFactory::getSession();
				
				if(!isset($_REQUEST['callback'])){
				
						$connection = new TwitterOAuth($params->get('ttappId',''), $params->get('ttsecret',''));
						$uri = JFactory::getURI ();
						$mainframe = JFactory::getApplication ();
						$uri->setVar ( 'callback', 1);
						$request_token = $connection->getRequestToken($uri->toString ());										
						
						$session->set('oauth_token',$request_token['oauth_token']);
						$session->set('oauth_token_secret',$request_token['oauth_token_secret']);
						/* If last connection failed don't display authorization link. */
					
						switch ($connection->http_code) {
						  case 200:
						  
							$url = $connection->getAuthorizeURL($request_token['oauth_token']);					
							$mainframe->redirect($url);
							break;
							
						  default:
							
							JHtmlBt_Socialconnect::response('Could not connect to Twitter. Refresh the page or try again later.');
						}
						
					}else{				
						
						if(isset($_REQUEST['oauth_token']) && $session->get('oauth_token') !== $_REQUEST['oauth_token']) {
							$session->clear('oauth_token');
							$session->clear('oauth_token_secret');							
						}
						else{
							
							$connection = new TwitterOAuth($params->get('ttappId',''), $params->get('ttsecret',''), $session->get('oauth_token'), $session->get('oauth_token_secret'));
							
							$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
							
							if (200 == $connection->http_code) {
								
								$user =  $connection->get('account/verify_credentials');								
								$user = JHtmlBt_Socialconnect::prepareData($user,'twitter');						
								$user = JHtmlBt_Socialconnect::assignProfile($user,$params->get ( 'tt-profiles' ));
								$user['access_token'] = serialize($access_token);							
								JHtmlBt_Socialconnect::checkUser($user,$params);
								
								$session->set('connection',serialize($connection));
								
								if($params->get('ttregister','automatic')=='automatic'){									
									JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
								
								}
								else{
									if(!empty(JFactory::getUser()->id)){
										JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
										JHtmlBt_Socialconnect::reloadParent($return_decode);
									}else{
										JHtmlBt_Socialconnect::loadDataForm($user);
									}
								}
							} else {
							  
								JHtmlBt_Socialconnect::response('Error:' . $connection->http_code);
							}
						}
					}			
				
			}
			if(isset($_REQUEST['state']) && $_REQUEST['state']=='sc_gg'){			 	 
				$token = JHtmlBt_Socialconnect::getTokenGG($code, $params->get('ggappId',''), $params->get('ggsecret',''), JURI::base(), 'authorization_code');
						
				$user = JHtmlBt_Socialconnect::getUserGG($token->access_token);				   	    
				$user = JHtmlBt_Socialconnect::prepareData($user,'google');
				$user = JHtmlBt_Socialconnect::assignProfile($user,$params->get ( 'gg-profiles' ));
				$user['access_token'] = $token->access_token;
				// check existing user 
					JHtmlBt_Socialconnect::checkUser($user,$params);
				if($params->get('ggregister','automatic')=='automatic'){					
				
					JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
					
				}
				else{
					if(!empty(JFactory::getUser()->id)){
					JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
					JHtmlBt_Socialconnect::reloadParent($return_decode);
					}
					else{
						JHtmlBt_Socialconnect::loadDataForm($user);
					}
				}
				
			
			}			
			if(isset($_REQUEST['state']) && $_REQUEST['state']=='sc_linkedin'){			
				$callback_url = JURI::root().'index.php?option=com_bt_socialconnect';				
				$token_url = JHtmlBt_Socialconnect::getTokenLinkedin($params->get('linkappId',''), $callback_url, $params->get('linksecret',''), $code);				
				$user = JHtmlBt_Socialconnect::fetch('GET',$token_url->access_token);
				$user = JHtmlBt_Socialconnect::prepareData($user,'linkedin');				
				$user = JHtmlBt_Socialconnect::assignProfile($user);
				$user['access_token'] = $token_url->access_token;				
				JHtmlBt_Socialconnect::checkUser($user,$params);
				if($params->get('linkregister','automatic')=='automatic'){	
				
					JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
					
				}
				else{
					if(!empty(JFactory::getUser()->id)){
					JHtmlBt_Socialconnect::authenticationSocial($params,$user);		   		
					JHtmlBt_Socialconnect::reloadParent($return_decode);
					}
					else{
						JHtmlBt_Socialconnect::loadDataForm($user);
					}
				}
			}
			
		 }
		 
		 //Check facebook login		
		 
		if(isset($_COOKIE['facebookid']) && $_COOKIE['facebookid']!="" ){		
			$faceid =$_COOKIE['facebookid'];			
			$userid = JHtmlBt_Socialconnect ::getUser($faceid);
			if($userid){
				$email =JHtmlBt_Socialconnect ::getEmail($userid);
				if($email){
					if (empty(JFactory::getUser()->id)){	
						JHtmlBt_Socialconnect ::loginSocial($email);
					}
				}
			}
		}	
		
		
		
	}
	
	public static function getToken($prefix, $name, $params=null)
	{
		if(is_null($params)){
			$plugin = JPluginHelper::getPlugin('system', 'bt_cronjob');
			$params = new JRegistry($plugin->params);
		}

		$token = trim( $params->get($name) );
		$config = JFactory::getConfig();
		$email = $config->get('config.mailfrom');
		$db = $config->get('config.db');

		return JFilterOutput::stringURLSafe(md5($prefix. $email. $db. $token));
	}
	
	public function processPost($items,$params){
		
		$dbo = JFactory::getDBO();
		$update_time =JFactory::getDate()->toSql();
		date_default_timezone_set($params->get('timezone'));
		$global = $params->get('schedule');
		$date =date('Y-m-d');		
		$now = date('H:i',strtotime('-24 hours', time()));		
		$now= explode(":", $now); 	
		foreach ($items as $item)
		{	
			if(!empty($item->params)){
				$available= json_decode($item->params);
				$db->setQuery($available->query);		
				$iSpublish =$db->loadResult();
			}else{
				$iSpublish =1;
			}
			if($iSpublish == 1){
				$cron = (object)unserialize($item->scron);
				$schedule = (object)unserialize($item->schedule);			
				$hour = preg_replace('/( *)/', '',$schedule->hour);
				$sche_date = preg_replace('/( *)/', '',$schedule->date);
				
				if($hour=='' && $global!=''){
					$hour = $global;
				}
				$hour = explode(":", $hour);
				
				if($schedule->date ==''|| $sche_date == $date){			
					if($hour[0] =='' || (int)$hour[0] < (int)$now[0] ||((int)$hour[0] == (int)$now[0] && (int)$hour[1]<=(int)$now[1]))
					{			
						$name = $cron->name;
						if(self::checkClass($cron->alias)){
							$publishes = new BTPublishes($cron->alias,$dbo);
							$result = $name::postMessage($publishes,$cron->attachment);
							if(!$dbo->connected()){
								$dbo = BTJFactory::createDbo();
							}
							if($result['checked'] == 1){							
								$query='UPDATE  #__bt_messages SET published =\'' . $result['publish']. '\',log =\'' . $result['log']. '\',sent_time= \''.$update_time.'\' WHERE id='.$item->id;
								$dbo->setQuery($query);
								$dbo->query();
							}
						}
					}			
				}
			}
		}
	
	}
	
	public static function checkClass($name){	
		$dbo = JFactory::getDBO();
		$query = 'SELECT * FROM #__bt_channels WHERE  published =1 AND alias ="'.$name.'"';		
		$dbo->setQuery($query);
		$items = $dbo->loadResult();
		return $items;
	}
	
	public static function getSocialID($id){	
		$dbo = JFactory::getDBO();
		$query = 'SELECT social_id FROM #__bt_connections WHERE social_type ="facebook" AND  user_id ="'.$id.'"';		
		$dbo->setQuery($query);
		$items = $dbo->loadResult();		
		return $items;
	}
}

?>