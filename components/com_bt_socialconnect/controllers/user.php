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

/**
 * Registration controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
 
class Bt_SocialconnectControllerUser extends Bt_SocialconnectController
{
	/**
	 * Method to log in a user.
	 *
	 * @since	1.6
	 */
	 
	public function login()
	{
	
		//JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));

		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		 
		// Populate the data array: 
		$data = array();
		$data['return'] = base64_decode($jinput->post->get('return', '', 'BASE64'));
		
		$data['username'] = $jinput->post->get('username', '', 'USERNAME');
		 
		$data['password'] = $jinput->post->get('password', '',JREQUEST_ALLOWRAW);
		
		// Set the return URL if empty.
		if (empty($data['return'])) {
			$data['return'] = 'index.php?option=com_bt_socialconnect&view=profile';
		}

		// Set the return URL in the user state to allow modification by plugins
		$app->setUserState('users.login.form.return', $data['return']);
		
		// Get the log in options.
		$options = array();
		$options['remember'] =$jinput->get('remember', false, 'BOOL');
		$options['return'] = $data['return'];

		// Get the log in credentials.
		$credentials = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password'];
		
		$session = JFactory::getSession();
		$user = $session->get('btPrepareUser');
		// Perform the log in.
		if (true === $app->login($credentials, $options)) {		
			if($user){
				$return = self::checkUser($data,$user);
				
			}
			// Success
			$app->setUserState('users.login.form.data', array());
			$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
		} else {
			// Login failed !
			$data['remember'] = (int)$options['remember'];
			$app->setUserState('users.login.form.data', $data);
			if($user){
				$app->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration', false));
			}else{
				$app->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
			}
		}
	}

	/**
	 * Method to log out a user.
	 *
	 * @since	1.6
	 */
	public function logout()
	{
		JSession::checkToken('request') or jexit(JText::_('JInvalid_Token'));
		$jinput = JFactory::getApplication()->input;
		$app = JFactory::getApplication();

		// Perform the log in.
		$error = $app->logout();
		
		// Check if the log out succeeded.
		if (!($error instanceof Exception)) {			
			$return = $jinput->get('return', '', 'base64');
			$return = base64_decode($return);
			if (!JURI::isInternal($return)) {
				$return = '';
			}
			// Redirect the user.
			$app->redirect(JRoute::_($return, false));
		} else {
			$app->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
		}
	}
	
	// If  user login and data has reloaded to form then save connect assign user login
	
	public  function checkUser($data,$user){		
			
		$user = (array) $user;
		//Set create time and update time 
		$user['created_time'] =JFactory::getDate()->toSql();
		$user['updated_time']='';
		$db		= JFactory::getDbo();
		$query ='SELECT id from #__users WHERE username ="'.$data['username'].'"';
		$db->setQuery($query);
		$db->query();	
		$uid = $db->loadResult();			
		if($uid){
			$query='INSERT INTO  #__bt_connections VALUES(\''.''.'\',\'' . ($uid) . '\',\'' . $user['socialId'] . '\', \''.($user['loginType']).'\',\''.($user['access_token']).'\',\''.''.'\',\''.$user['enabled_publishing'].'\', \''.($user['created_time']).'\', \''.($user['updated_time']).'\')';
			$db->setQuery($query);
			$db->query();
		}
		
		
	}

	
	
}
