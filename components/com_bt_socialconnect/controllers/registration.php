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
class Bt_SocialconnectControllerRegistration extends Bt_SocialconnectController
{
	/*
	* Activate user account	
	*/
	public function activate()
	{
		$user		= JFactory::getUser();
		$uParams	= JComponentHelper::getParams('com_users');
		$jinput = JFactory::getApplication()->input;
	
		// If user registration or account activation is disabled, throw a 403.
		if ($uParams->get('useractivation') == 0 || $uParams->get('allowUserRegistration') == 0) {
			JError::raiseError(403, JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
			return false;
		}

		$model = $this->getModel('Registration', 'Bt_SocialconnectModel');		
		$token = $jinput->get('token',null , 'alnum');

		
		if ($token === null || strlen($token) !== 32) {
			JError::raiseError(403, JText::_('JINVALID_TOKEN'));
			return false;
		}
			// Attempt to activate the user.
		$return = $model->activate($token);		
		
		if ($return === false) {
			// Redirect back to the homepage.
			$this->setMessage(JText::sprintf('COM_BT_SOCIALCONNECT_REGISTRATION_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect('index.php');
			return false;
		}

		$useractivation = $uParams->get('useractivation');

		// Redirect to the login screen.
		if ($useractivation == 0)
		{
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_SAVE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
		}
		elseif ($useractivation == 1)
		{
			if ($user->get('id')) {
				$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_ACTIVATE_SUCCESS_LOGED'));
				$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile', false));
			}else{
				$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_ACTIVATE_SUCCESS'));
				$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
			}
		}
		elseif ($return->getParam('activate'))
		{
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_VERIFY_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration&layout=complete', false));
		}
		else
		{
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_ADMINACTIVATE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration&layout=complete', false));
		}
		return true;
	}
	
	/**
	 * Method to register a user.
	 *
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function register()
	{
		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$jinput = JFactory::getApplication()->input;
		
		if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) {
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
			return false;
		}
		
		// Initialise variables.
		
		$app	= JFactory::getApplication();
		$model	= $this->getModel('Registration', 'Bt_SocialconnectModel');	
		$params = JComponentHelper::getParams('com_bt_socialconnect');	
		$requireData = array();			
		
		// Get the user data.
		$requestData = $jinput->post->get('jform', array(), 'array');
		$user_fields = $jinput->post->get('user_fields', array(), 'array');		
		
		// Validate the posted data.
		$form	= $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data	= $model->validate($form, $requestData);
		
		$requireData = $requestData;
		$requireData['user_fields'] = $user_fields;
		// Check for validation errors.
		if ($data === false) {			
			$errors	= $model->getErrors();

			
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}
			
			// Save the data in the session.
			$app->setUserState('com_bt_socialconnect.registration.data', $requireData);
			
			// Redirect back to the registration screen.
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration', false));
			return false;
		}
		
		$data['user_fields'] = $user_fields;
		
		// Attempt to save the data.
		$return	= $model->register($data);		
		
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_bt_socialconnect.registration.data', $data);	

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('COM_BT_SOCIALCONNECT_REGISTRATION_SEND_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration', false));
			return false;
		}
		
		// Flush the data from the session.
		$app->setUserState('com_bt_socialconnect.registration.data', null);
		
		// Redirect to the profile screen.
		
		if ($return === 'adminactivate'){
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_COMPLETE_VERIFY'));
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration&layout=complete', false));
		} elseif ($return === 'useractivate') {
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_COMPLETE_ACTIVATE'));
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=registration&layout=complete', false));
		} else {
			$this->setMessage(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_SAVE_SUCCESS'));
			if($params->get('userautologin',1) =='1'){
				if($params->get('loginredirection','')!=''){
					$this->setRedirect($params->get('loginredirection'), false);
				}
				else{
					$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile', false));
				}
			}else{
				$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
			}
		}

		return true;
	}
	//Unset session if click button exit
	public function cancel(){
		$session = JFactory::getSession();		
		$session->clear('btPrepareUser');
		$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
	}
	
	
}