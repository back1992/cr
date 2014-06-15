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

jimport('joomla.application.component.modelform');
jimport('joomla.access.access');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
$com_path = '/components/com_bt_socialconnect/';
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR. $com_path . '/models', 'Bt_SocialconnectModel');
JModelLegacy::addIncludePath(JPATH_SITE. $com_path . '/models', 'Bt_SocialconnectModel');


abstract class JHtmlBt_Socialconnect{

	public static $messages = array();
	public static function showSocialButtons($type){
		
		require_once(JPATH_SITE . '/components/com_bt_socialconnect/controllers/socialconnect.php');
		$params = JComponentHelper::getParams('com_bt_socialconnect');						
		
		switch($type){		
			case'facebook':	
				
				if($params->get('fbactive')==1){
					
					$fbUr = Bt_SocialconnectControllerSocialconnect ::getlink('facebook');			
					return '<a href="JavaScript:newPopup(\''.$fbUr.'\',\'Connecting to Facebook ...\',600,400);"><div class="btnsc btn-fb"><span class="iconsc icon-fb"></span><span class="icon-border-sc icon-border-fb"></span><span class="text-sc">Facebook</span></div></a>';
				}
				break;
			case'twitter':
				if($params->get('ttactive')==1){
					
					$ttUr = Bt_SocialconnectControllerSocialconnect ::getlink('twitter');				
					return '<a href="JavaScript:newPopup(\''.$ttUr.'\',\'Connecting to Twitter ...\',600,400);"><div class="btnsc btn-tt"><span class="iconsc icon-tt"></span><span class="icon-border-sc icon-border-tt"></span><span class="text-sc">Twitter</span></div></a>';
				}				
				break;
			case'google':
				
				if($params->get('ggactive')==1){
				
					$ggUr = Bt_SocialconnectControllerSocialconnect ::getlink('google');				
					return '<a href="JavaScript:newPopup(\''.$ggUr.'\',\'Connecting to Google ...\',600,400);"><div class="btnsc btn-gg"><span class="iconsc icon-gg"></span><span class="icon-border-sc icon-border-gg"></span><span class="text-sc">Google</span></div></a>';
				}
				break;
			case'linkedin':
				
				if($params->get('linkactive')==1){
				
					$linkedin = Bt_SocialconnectControllerSocialconnect ::getlink('linkedin');				
					return '<a href="JavaScript:newPopup(\''.$linkedin.'\',\'Connecting to linkedin ...\',600,400);"><div class="btnsc btn-in"><span class="iconsc icon-in"></span><span class="icon-border-sc icon-border-in"></span><span class="text-sc">Linkedin</span></div></a>';
				}
				break;
			
		
		}
	
	}
	
	public static function getTokenUrl($appfb_id,$callback_url,$appfb_secret,$code){
		 return "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $appfb_id . "&redirect_uri=" . urlencode($callback_url)
       . "&client_secret=" . $appfb_secret ."&code=" . $code;
	}
	
	public  static function getTokenGG($code, $client_id, $client_secret, $redirect_uri, $grant_type){
		 	$url = 'https://accounts.google.com/o/oauth2/token';
			$fields = array(
			        'code' => $code,
			        'client_id' => $client_id,
			    	'client_secret' =>$client_secret,
			    	'redirect_uri' => $redirect_uri,
			    	'grant_type' => $grant_type
    				);
    				
    		$fields_string = '';		
    		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$fields_string = rtrim($fields_string, '&');
			$response = self::curlResponse($url,$fields_string) ;
			
			return json_decode($response);
			
	}
	
	public  static function getTokenLinkedin($client_id, $redirect_uri, $client_secret, $code){
			$params = array('grant_type' => 'authorization_code',
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'code' => $code,
                'redirect_uri' => $redirect_uri,
			);     
				
			$url = 'https://www.linkedin.com/uas/oauth2/accessToken?' . http_build_query($params);				
			$response =self::curlResponse($url);		
						 
		return json_decode($response);
			
	}
	
	public static function getUserGG($access_token)
	{
		$url = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$access_token.'&alt=json';
		$user = self::curlResponse($url);
		$user= json_decode($user);
		return $user;
	}
	
	public static function fetch($method, $access_token) {
		$params = array('oauth2_access_token' => $access_token,
                    'format' => 'json',
              );        
		$url = 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address,industry,public-profile-url,picture-url,date-of-birth,phone-numbers,summary,interests,skills,location,formatted-name,maiden-name,positions,current-status)?' . http_build_query($params);
    
		$response =self::curlResponse($url);  
		return json_decode($response);
	}
	
	public static function curlResponse($url,$data = null){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
			if($data){
				curl_setopt($ch,CURLOPT_POST, true);
				curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
			}
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}
	
		
	public static function prepareData($user,$type){	
		$data = array();
		$data['user_fields'] = array();				
		$user_fields = self::loadUserFields();		
		switch($type){
			case 'facebook':				
				$data ['id'] = $user->id;
				$data ['user_id'] ='';
				$data ['name'] = $user->name;
				$data ['username'] =  $user->username;
				$data ['email'] =  $user->email;				
				$user->picture = 'http://graph.facebook.com/'.$user->id.'/picture?type=large';				
				$user->location = isset($user->location->name)? $user->location->name:'';				
				
				foreach($user_fields AS $key =>$value){
					
					$data['user_fields'][$value->alias]= self::assignValueSocial($value,$user, $value->facebook);
					
				}				
			
			break;
			case 'google':			
				$data ['id'] = $user->id;
				$data ['user_id'] ='';
				$data ['name'] = $user->name;
				list($data ['username']) = explode('@',$user->email);
				$user->username = $data['username'];
				$data ['email'] = $user->email;					
				
				foreach($user_fields AS $key =>$value){					
						$data['user_fields'][$value->alias]= self::assignValueSocial($value,$user, $value->google);
					
				}					
				
			break;
			case 'twitter':		
				
				$data ['id'] = $user->id;
				$data ['user_id'] ='';
				$data ['name'] = $user->name;
				$data ['email'] =  $user->screen_name.'@twitter.com';
				$data ['username'] =  $user->screen_name;			
				$user->username =  $user->screen_name;	
				$user->picture = isset($user->profile_image_url)? $user->profile_image_url:'';
				$user->website = $user->url;
				$user->about = $user->description;
				$user->link = 'https://twitter.com/'.$user->screen_name;				
				
				foreach($user_fields AS $key =>$value){					
						$data['user_fields'][$value->alias]= self::assignValueSocial($value,$user, $value->twitter);
					
				}					
					
			break;
			case 'linkedin':
				
				$data['id']= $user->id;
				$data ['user_id'] ='';
				$data ['name'] = $user->formattedName;
				$data ['email'] =$user->emailAddress;
				$data ['username'] = $user->firstName.$user->lastName;
				$user->username = $data ['username'];
				$user->birthday =isset($user->dateOfBirth)? $user->dateOfBirth->year.'-'.$user->dateOfBirth->month.'-'.$user->dateOfBirth->day:'';				
				$user->location = isset($user->location->name)? $user->location->name:'';
				$user->picture = isset($user->pictureUrl)? $user->pictureUrl:'';
				
				$user->quotes= isset($user->interests)? $user->interests:'';
				$user->about = isset($user->summary) ? $user->summary:'';
				$user->link = isset($user->publicProfileUrl) ? $user->publicProfileUrl :'';	
				
				foreach($user_fields AS $key =>$value){					
						$data['user_fields'][$value->alias]= self::assignValueSocial($value,$user, $value->linkedin);
					
				}
			break;
		}
		$data['loginType'] = $type;
		$data['rawData'] = serialize($user);
		return $data;
	}
	
	public static function assignProfile($user) {	
	
		$params = JComponentHelper::getParams('com_bt_socialconnect');	
		$data = array ();		
		$data ['user_id'] = $user['user_id'];
		$data['name'] =		$user['name'];
		switch($params->get('usernametype','auto')){
			case'auto':
				$data ['username'] = $user['username'];
				break;
			case'psocialid':
				switch($user['loginType']){
					case'facebook':
						$data ['username'] ='fb_'.$user['id'];
						break;
					case'twitter':
						$data ['username'] ='tt_'.$user['id'];
						break;
					case'google':
						$data ['username'] ='gg_'.$user['id'];
						break;
					case'linkedin':
						$data ['username'] ='li_'.$user['id'];
						break;
				}
				break;
			case'psocialusername':
					switch($user['loginType']){
					case'facebook':
						$data ['username'] ='fb_'.$user['username'];
						break;
					case'twitter':
						$data ['username'] ='tt_'.$user['username'];
						break;
					case'google':
						$data ['username'] ='gg_'.$user['username'];
						break;
					case'linkedin':
						$data ['username'] ='li_'.$user['username'];
						break;
				}
				break;
			case'socialid':				
					$data ['username'] =$user['id'];				
				break;
			case'socialusername':				
					$data ['username'] =$user['username'];					
				break;
			case'socialemail':
				$data ['username']=$user['email'];
				break;
		}		
		if($params->get('remove_user',0)){		
			$data['username'] = $user['email'];
		}
		$data ['email1'] = $user['email'];
		$data ['email2'] = $user['email'];
		$data ['user_fields'] = $user['user_fields'];		
		$data ['socialId'] = $user['id'];
		$data ['loginType'] = $user['loginType'];
		$data ['enabled_publishing']= $params->get('enabled_publishing',1);
		$data ['created_time'] = JFactory::getDate()->toSql();
		$data ['updated_time'] = '';	
		$data ['rawData'] = $user['rawData'];		
		$password = uniqid();		
		$data ['password1'] = $password;
		$data ['password2'] = $password;		
		
     	return $data;
	}	
	
	
	public static function checkUser($user,$params){	
		
		if($user['socialId']==''){
			self::response('Could not get user data. Please try again later.');
		}
		$lang = JFactory::getLanguage();
		$lang->load('com_bt_socialconnect', JPATH_SITE);
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		$query->select("a.user_id");
		$query->from("#__bt_connections as a");
		$query->join('INNER', '#__users AS u on u.id = a.user_id');
		$query->where ( 'a.social_id=' . $db->quote ( $user['socialId'] ) );
		$query->where ( 'a.social_type=' . $db->quote ( $user['loginType'] ) );		
		$db->setQuery ($query);
		
		$userid = $db->loadResult();		
		$query	= $db->getQuery(true);
		$query->select("id,email,block,activation");
		$query->from("#__users");
		if($userid){
			$query->where ( 'id=' . $db->quote ( $userid ) );
		}else{
			$query->where ( 'email=' . $db->quote ( $user['email1'] ) );
		}		
		$db->setQuery ($query);
		
		$chkuser = $db->loadObject();		
		
		if($chkuser){
			if($chkuser->block ==1){
				if($chkuser->activation){
					self::unblockUser($chkuser->email);
					self::loginSocial($chkuser->email);
					self::reloadParent();
				}else{
					self::ajaxResponse(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_BLOCKED'));
				}
			}
			else{
				if (!empty(JFactory::getUser()->id) && JFactory::getUser()->id !=(int) $chkuser->id){
					self::ajaxResponse(JText::_('COM_BT_SOCIALCONNECT_CONNECT_FALSE'));
				
					return false;
				}
				if(self::checkSocialID($user['socialId'],$user['loginType'],$chkuser->id)==''){			
						$query='INSERT INTO  #__bt_connections VALUES(\''.''.'\',\'' . ($chkuser->id) . '\',\'' . $user['socialId'] . '\', \''.($user['loginType']).'\',\''.($user['access_token']).'\',\''.''.'\',\''.$user['enabled_publishing'].'\', \''.($user['created_time']).'\', \''.($user['updated_time']).'\')';
						$db->setQuery($query);
						$db->query();
						
				}				
			
				self::loginSocial($chkuser->email);
				self::reloadParent();			
			}
		}
	
	}
	
	public static function unblockUser($email) {
		$db = JFactory::getDbo ();
		$query = 'UPDATE `#__users` SET `block` ="0" , `activation` = "" WHERE `email`="' . $email . '"';
		$db->setQuery ( $query );
		$db->query ();
	}
	
	public static function checkSocialID($socialId,$socialType,$userid){
		
		$db		= JFactory::getDbo();
		$query = 'SELECT social_id FROM #__bt_connections WHERE social_id = ' ."'$socialId'".' AND social_type ='."'$socialType'".' AND user_id ='.$userid.'';
		
		$db->setQuery($query);
		$db->query();		
		return $db->loadResult();	
	}
	
	public static function authenticationSocial($params,$userSocial){
		
		self::registerSocial($userSocial);
		#login with new user
		$mainframe = JFactory::getApplication('site');
		$options = array();
		$credentials['username'] = $userSocial['username'];
		$credentials['password'] = $userSocial['password1'];
		$mainframe->login($credentials,$options);
		self::reloadParent();
		
	}
	
	public static function registerSocial($userSocial){	

	$params = JComponentHelper::getParams('com_users');	
	$activation = JComponentHelper::getParams('com_bt_socialconnect');	
	
	$data= array();	
	foreach ($userSocial as $k => $v) {
			$data[$k] = $v;
	}	
		$data ['email'] = $data['email1'];
		$data ['password'] = $data['password1'];
		
		if($activation->get('ignore_activate',1)== 1){
			$useractivation = 0;
		}else{
			$useractivation = $params->get('useractivation');
		}
		if (($useractivation == 1) || ($useractivation == 2)) {				
				$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
				$data['block'] = 1;				
			}	
			else{
				$data['activation']='';
				$data ['block'] = 0;	
		}
		
				
		$system	= $params->get('new_usertype', 2);
		$data['groups'] = array($system);	
			
		if (!empty(JFactory::getUser()->id)){			
			$db		= JFactory::getDbo();
			$userId =JFactory::getUser()->id;
			$query='INSERT INTO  #__bt_connections VALUES(\''.''.'\',\'' . ($userId) . '\',\'' . $data['socialId'] . '\', \''.($data['loginType']).'\',\''.($data['access_token']).'\',\''.''.'\',\''.$data ['enabled_publishing'].'\', \''.($data['created_time']).'\', \''.($data['updated_time']).'\')';
			$db->setQuery($query);
			$db->query();
		}
		else{
			if(self::checkSocialUsername($data['username'])!=''){	
				$data['username']= $data ['email'];
			}
			
			$model	=  JModelLegacy::getInstance('Socialconnect', 'Bt_SocialconnectModel');		
			if($model->save($data)){
				self::sendMail($data);				
				self::reloadParent();				
			}
		}
	
	}
	public static function checkSocialUsername($username){
		
		$db		= JFactory::getDbo();
		$query = 'SELECT username FROM #__users WHERE username = ' ."'$username'";		
		$db->setQuery($query);
		$db->query();		
		return $db->loadResult();	
	}
	public static function loadDataForm($user){
		$session = JFactory::getSession();
		$data =(object)$user;
		$session->set('btPrepareUser', $data);
		echo '<script type="text/javascript">window.opener.location.href="index.php?option=com_bt_socialconnect&view=registration";window.close();</script>';
	}
	
	public static function ajaxResponse($message){
		$obLevel = ob_get_level();
		if($obLevel){
			while ($obLevel > 0 ) {
				ob_end_clean();
				$obLevel --;
			}
		}
		//echo '<script type="text/javascript">window.close();</script>';
		$alert = "alert('".$message."');";		
		echo '<script type="text/javascript">'.$alert.'window.close();</script>';
		exit;
	}
	public static  function response($message){
		echo $message;
		exit;
	}
	
	public static function sendMail($data){	
	
		$config = JFactory::getConfig();
		$db = JFactory::getDBO();
		$lang = JFactory::getLanguage();
		$lang->load('com_bt_socialconnect', JPATH_SITE);
		$params = JComponentHelper::getParams('com_users');
		$activation =JComponentHelper::getParams('com_bt_socialconnect');
		
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::root();
		
		if($activation->get('ignore_activate',1)== 1){
			$useractivation =0;
		}else{
			$useractivation = $params->get('useractivation');
		}
		$sendpassword = $params->get('sendpassword', 1);	
		
		// Handle account activation/confirmation emails.
		
		if ($useractivation == 2)
		{			
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'], false);
	
			$emailSubject	= JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);
			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['siteurl'].'index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'],
					$data['siteurl'],
					$data['username'],
					$data['password']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['siteurl'].'index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'],
					$data['siteurl'],
					$data['username']
				);
			}
			
		}
		elseif ($useractivation == 1)
		{
			
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'], false);
		
			$emailSubject	= JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);			
			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['siteurl'].'index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'],
					$data['siteurl'],
					$data['username'],
					$data['password']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['siteurl'].'index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'],
					$data['siteurl'],
					$data['username']
				);
			}
		}
		else
		{

			$emailSubject	= JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);
			
			if ($sendpassword)
			{
				$emailBody = JText::sprintf(
					'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_BODY',
					$data['name'],
					$data['sitename'],
					$data['siteurl'],
					$data['username'],
					$data['password']
				);
			}
			else
			{
				$emailBody = JText::sprintf(
					'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_BODY_NOPW',
					$data['name'],
					$data['sitename'],
					$data['siteurl']
				);
			}
		}
		
		// Send the registration email.
		$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
		
		if ($params->get('mail_to_admin') == 1) {
			$emailSubject = JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACCOUNT_DETAILS',
				$data['name'],
				$data['sitename']
			);

			$emailBodyAdmin = JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY',
				$data['name'],
				$data['username'],
				$data['siteurl']
			);

			// get all admin users
			$query = 'SELECT name, email, sendEmail' .
					' FROM #__users' .
					' WHERE sendEmail=1';

			$db->setQuery( $query );
			$rows = $db->loadObjectList();

			// Send mail to all superadministrators id
			foreach( $rows as $row )
			{
				$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBodyAdmin);

				// Check for an error.
				if ($return !== true) {
					self::enqueueMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
				}
			}
		}
		if ($return !== true) {			
			
			$q = "SELECT id
				FROM #__users
				WHERE block = 0
				AND sendEmail = 1";
			$db->setQuery($q);
			$sendEmail = $db->loadColumn();
			if (count($sendEmail) > 0) {
				$jdate = new JDate();
				
				$q = "INSERT INTO ".$db->quoteName('#__messages')." (".$db->quoteName('user_id_from').
				", ".$db->quoteName('user_id_to').", ".$db->quoteName('date_time').
				", ".$db->quoteName('subject').", ".$db->quoteName('message').") VALUES ";
				$messages = array();

				foreach ($sendEmail as $userid) {
					$messages[] = "(".$userid.", ".$userid.", '".$jdate->toSql()."', '".JText::_('COM_BT_SOCIALCONNECT_MAIL_SEND_FAILURE_SUBJECT')."', '".JText::sprintf('COM_BT_SOCIALCONNECT_EMAIL_SEND_FAILURE_BODY', $return, $data['username'])."')";
				}
				$q .= implode(',', $messages);
				$db->setQuery($q);
				$db->query();
			}
			self::enqueueMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_SEND_MAIL_FAILED'));
		}
		
	}
	
	public static function loginSocial($email) {
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication ();
		
		$sql = "SELECT * FROM #__users WHERE email = " . $db->quote ( $email );
		$db->setQuery ( $sql );
		$result = $db->loadObject ();
		
		$jUser = JFactory::getUser ( $result->id );
		$instance = $jUser;
		$instance->set ( 'guest', 0 );
		// Register the needed session variables
		$session = JFactory::getSession();
		$session->set ( 'user', $jUser );
		// Check to see the the session already exists.                        
		$app->checkSession ();
		
		$db->setQuery ( 'UPDATE ' 
			. $db->quoteName ( '#__session' ) 
			. ' SET ' . $db->quoteName ( 'guest' ) 
			. ' = ' 
			. $db->quote ( $instance->get ( 'guest' ) ) 
			. ',' . '   ' 
			. $db->quoteName ( 'username' ) 
			. ' = ' . $db->quote ( $instance->get ( 'username' ) ) 
			. ',' . '   ' 
			. $db->quoteName ( 'userid' ) 
			. ' = ' 
			. ( int ) $instance->get ( 'id' ) 
			. ' WHERE ' . $db->quoteName ( 'session_id' ) . ' = ' . $db->quote ( $session->getId () ) );
		$db->query (); 
		
		// Hit the user last visit field
		$instance->setLastVisit ();
	}
	
	public static function enqueueMessage($text){
		self::$messages[] = $text;
	}
	
	public static function reloadParent(){
		$params = JComponentHelper::getParams('com_bt_socialconnect');		
		$alert = '';		
		if(count(self::$messages)) $alert = "alert('".addslashes(implode("\n",self::$messages))."');";
			if($params->get('loginredirection','')!=''){
				$redirect =$params->get('loginredirection','');
			}
			else{
				$redirect ='index.php?option=com_bt_socialconnect&view=profile';
			}
			echo '<script type="text/javascript">'.$alert.'window.opener.location.href="'.$redirect.'";window.close();</script>';
		exit;
	}
	
	public static function getUser($fbid){
		$db = JFactory::getDbo();
		$db->setQuery('SELECT user_id,social_type FROM #__bt_connections WHERE social_id = '.$fbid);
		$items = $db->loadResult();
		return $items;
	
	}
	public static function getEmail($userid){	
		$db = JFactory::getDbo();
		$db->setQuery('SELECT email FROM #__users WHERE id = '.$userid);
		$items =  $db->loadResult();
		return $items;
	
	}
	public static function assignValueSocial($items,$user, $type){
		$array = array();
		
		switch($type){
			case'id':
				$items->alias = $user->id;
				break;
			case'name':
				$items->alias =$user->name;
				break;
			case'username':
				$items->alias =$user->username;
				break;
			case'email':
				$items->alias =$user->email;
				break;
			case'link':
				$items->alias = isset($user->link)? $user->link:'';
				break;
			case'picture':			
				if(isset($user->picture) && $user->picture!=''){
					
					$saveDir =JPATH_SITE .'/images/bt_socialconnect/';
					$images_path = $saveDir .'avatar/';					
					self::prepareFolders($saveDir);			
					self::prepareFolders($images_path);	
					$path_image_avartar = $images_path;
					
					$saveto = $path_image_avartar.'image_' . md5($user->picture) .'_('.$user->username.'_'.$items->alias.')_' . '.jpg';
					if(file_exists($saveto)) unlink($saveto);
					
					$fp = fopen($saveto,'wb');
					fwrite($fp, self::getContentUrl($user->picture));
					fclose($fp);					
										
					$dispatcher = JDispatcher::getInstance();
					JPluginHelper::importPlugin('btsocialconnect');	
					$results = $dispatcher->trigger('onBtSocialconnectSave', array($user,$items));	
					$items->alias='image_' . md5($user->picture) .'_('.$user->username.'_'.$items->alias.')_' . '.jpg';
				}
			
				break;
			case'birthday':
				if(isset($user->birthday) && !empty($user->birthday)){
					$items->alias =date("Y-m-d", strtotime($user->birthday));
				}
				else{
					$items->alias ='';
				}							
				break;
			case'location':
				$items->alias = isset($user->location)? $user->location:'';
				break;
			case'website':
				$items->alias =isset($user->website)? $user->website:'';
				break;
			case'gender':			
				$gender = isset($user->gender)? $user->gender:'';					
				if(is_array($items->default_values)){
					if(isset($items->default_values['value']) && is_array($items->default_values['value'])){
						foreach($items->default_values['value'] AS $key=> $value){								
							if(strtolower($value) == strtolower($gender)){
									$items->alias = $value;
									break;
							}else{
								$items->alias = $gender;
							}
						}
					}
					else{
						$items->alias = $gender;
					}							
					
				}else{				
					$items->alias = $gender;
				}
				break;
			case'quotes':
				$items->alias = isset($user->quotes)? $user->quotes:'';
				break;
			case'about':
				$items->alias = isset($user->about)? $user->about:'';
				break;
			default :
				$items->alias = $items->value;
				break;
		}
		return $items->alias;
	
	}
	
	public static function loadUserFields()
	{
		$app = JFactory::getApplication();			
		
		$db = JFactory::getDBO();					
		$db->setQuery('SELECT * FROM #__bt_user_fields WHERE published = 1  AND show_registration = 1 order by ordering ');
		$r = $db->loadObjectList();		
			foreach ($r as $e)
			{							
					switch($e->type){										
						case 'dropdown':
							if(is_array($e->default_values)) $e->default_values = implode(',',$e->default_values);
							$e->default_values =@unserialize($e->default_values);
							$e->value = '';
							break;
						default:
							$e->value = $e->default_values;
							break;
					}
				
			}				
            return $r;
        
	}
	
	public static function prepareFolders($path)
	{
		self::createFolder($path);		
	}
	
	public static function createFolder($path){
		if (!is_dir($path))
		{
			JFolder::create($path, 0755);
			$html = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write($path.'index.html', $html);
		}
	}
	
	public static function getContentUrl($url) {
	
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	  curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	  curl_setopt($ch, CURLOPT_AUTOREFERER, false);
	  curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
	  curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	  curl_setopt($ch, CURLOPT_HEADER, 0);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	  $file = curl_exec($ch);
	  if($file === false) trigger_error(curl_error($ch));

	  curl_close ($ch);
	  return $file;
	}


}
?>