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

require_once JPATH_COMPONENT.'/controller.php';
require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/social.php');	
class Bt_SocialconnectControllerProfile extends Bt_SocialconnectController
{
	/**
	 * Method to check out a user for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	 
	public function edit()
	{
	
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$loginUserId	= (int) $user->get('id');
		$jinput = JFactory::getApplication()->input;
		
		// Get the previous user id (if any) and the current user id.
		$previousId = (int) $app->getUserState('com_bt_socialconnect.edit.profile.id');	
		
		// Check if the user is trying to edit another users profile.
		$userId	= (int)  $jinput->getInt('user_id', null, '', 'int');			
		if ($userId != $loginUserId) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
		
		// Set the user id for the user to edit in the session.
		$app->setUserState('com_bt_socialconnect.edit.profile.id', $userId);
		
		// Get the model.
		$model = $this->getModel('Profile', 'Bt_SocialconnectModel');
		
		// Check out the user.
		if ($userId) {
			$model->checkout($userId);
		}

		// Check in the previous user.
		if ($previousId) {
			$model->checkin($previousId);
		}
		
		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile&layout=edit', false));
	}
	
	public function getInfouser($user_id){
		$db = JFactory::getDBO();
			$query = 'SELECT username, email' .
					' FROM #__users' .
					' WHERE id='.$user_id;
			$db->setQuery( $query );
			$rows = $db->loadObject();	
		return $rows;
	}
	
	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	 
	public function save()
	{	
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$jinput = JFactory::getApplication()->input;
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Profile', 'Bt_SocialconnectModel');
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		$user	= JFactory::getUser();
		$userId	= (int) $user->get('id');
		$requireData = array();	
		// Get the user data.
		$data = $jinput->post->get('jform', array(), 'array');		
		$user_fields = $jinput->post->get('user_fields', array(), 'array');
		//Check remove user field
		if($params->get('remove_user')){
			$data['email2']= $data['email1'];
		}
		
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data = $model->validate($form, $data);		
		// Force the user_id to this user.	
		$requireData = $data;
		$requireData['user_fields'] = $user_fields;
		
		if ($data === false) {
		
			$errors	= $model->getErrors();
		
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

		
			$app->setUserState('com_bt_socialconnect.edit.profile.data', $requireData);

		
			$userId = (int) $app->getUserState('com_bt_socialconnect.edit.profile.id');
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile&layout=edit&user_id='.$userId, false));
			return false;
		}
		$data['user_id'] = $userId;
		$data['user_fields'] = $user_fields;
		
		// Validate the posted data.
		$return	= $model->save($data);
		
		// Check for errors.
		if ($return === false) {			
			$app->setUserState('com_bt_socialconnect.edit.profile.data', $data);
			
			// Redirect back to the edit screen.
			$userId = (int)$app->getUserState('com_bt_socialconnect.edit.profile.id');
			$this->setMessage(JText::sprintf('COM_BT_SOCIALCONNECT_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile&layout=edit&user_id='.$userId, false));
			return false;
		}
		
		switch ($this->getTask()) {
			case 'apply':
				// Check out the profile.
				$app->setUserState('com_bt_socialconnect.edit.profile.id', $return);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_PROFILE_SAVE_SUCCESS'));
				$this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_bt_socialconnect.edit.profile.redirect')) ? $redirect : 'index.php?option=com_bt_socialconnect&view=profile&layout=edit&hidemainmenu=1', false));
				break;

			default:
				// Check in the profile.
				$userId = (int)$app->getUserState('com_bt_socialconnect.edit.profile.id');
				if ($userId) {
					$model->checkin($userId);
				}

				// Clear the profile id from the session.
				$app->setUserState('com_bt_socialconnect.edit.profile.id', null);

				
				$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_PROFILE_SAVE_SUCCESS'));
				$this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_bt_socialconnect.edit.profile.redirect')) ? $redirect : 'index.php?option=com_bt_socialconnect&view=profile&user_id='.$return, false));
				break;
		}

		// Flush the data from the session.
		$app->setUserState('com_bt_socialconnect.edit.profile.data', null);
	}
	
	// Reset user Social connect
	
	public function reset(){
		
		$mainframe    = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		$jinput = JFactory::getApplication()->input;
		$model	= $this->getModel('Profile', 'Bt_SocialconnectModel');
		
		//get accestoken ,user id type social from link 
		$access_token	=  $jinput->get->get('access_token', '', 'STRING');		
		$user_id	= (int) $jinput->getInt('user_id');		
		$social	=  $jinput->get('social');	
		
		switch($social){
		
		case 'google':		
			$user = JHtmlBt_Socialconnect::getUserGG($access_token);			
			//Get user profile assign data
			$user = JHtmlBt_Socialconnect::prepareData($user,'google');			
					
		break;			
		case'facebook':	
			/*
			* refresh access token after reset account and save new access token in database  
			*/
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id=".$params->get('fbappId','')."&client_secret=".$params->get('fbsecret','')."&grant_type=fb_exchange_token&fb_exchange_token=".$access_token;
			$contents =self::file_get_content($token_url);
			$paramsfb = null;
			parse_str($contents, $paramsfb);
			$access_token = $paramsfb['access_token'];
			if($access_token){				
				$graph_url = "https://graph.facebook.com/me?access_token=".$access_token;	
				
				$user = JHtmlBt_Socialconnect::curlResponse($graph_url);
				$user = json_decode($user);				
				$user = JHtmlBt_Socialconnect::prepareData($user,'facebook');	
				if($user){	
					$model->updateAccesstoken($user['id'],$access_token);
											
				}
			}
			break;		
		case'twitter':	
			
			$info =unserialize($access_token);			
			if(!class_exists('TwitterOAuth')){
					require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/twitter/twitteroauth.php');	
			}
			$connection = new TwitterOAuth($params->get('ttappId',''), $params->get('ttsecret',''), $info['oauth_token'], $info['oauth_token_secret']);			
			$user =  $connection->get('account/verify_credentials');			
			$user = JHtmlBt_Socialconnect::prepareData($user,'twitter');
							
			break;	
		case'linkedin':		
			
			$user = JHtmlBt_Socialconnect::fetch('GET',$access_token);
			if(isset($user->status)){
				$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_PROFILE_RESET_FAILED'));
				$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile&user_id='.$user_id, false));
				return;
			}
			$user = JHtmlBt_Socialconnect::prepareData($user,'linkedin');
					
			break;
		}
		
		$user['user_id'] =$user_id;	
		$rows = self::getInfouser($user_id);						
		$user = JHtmlBt_Socialconnect::assignProfile($user);
		$user['username'] =$rows->username;
		$user['email1'] =$rows->email;
		$user['password1']='';
		unset($user['password2']);
					
		$return	= $model->save($user);
		if ($return !== true) {
			$this->setError(JText::_('COM_BT_SOCIALCONNECT_PROFILE_RESET_FAILED'));
			return false;
		}else{			
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_PROFILE_RESET_SUCCESS'));
		}
		$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile&user_id='.$return, false));
	
	}
	
	/*
	* disconnect social connect of user
	*/
	public function delete(){
		$jinput = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$id			= (int)$jinput->getInt('id');	
		$socialID		=(int) $jinput->getInt('socialId');	
		$query='DELETE FROM #__bt_connections WHERE id ='.$id;
		$db->setQuery($query);
		$db->query();	
		$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_DISCONNECT_SUCCESS'));
		$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile&layout=edit', false));
	}
	
	function file_get_content($token_url){
		$c = curl_init();
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_URL, $token_url);
		$contents = curl_exec($c);
		$err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
		curl_close($c);
		return $contents;
	}	
	
}
