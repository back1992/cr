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
jimport('joomla.event.dispatcher');
$com_path = '/components/com_bt_socialconnect/';
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR. $com_path . '/models', 'Bt_SocialconnectModel');

class Bt_SocialconnectModelRegistration extends JModelForm
{
	/**
	 * @var		object	The user registration data.
	 * @since	1.6
	 */
	protected $data;
	protected $images_path;
	
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->images_path = JPATH_SITE .'/images/avatar/';	
		
		
	}
	public function activate($token)
	{
		$config	= JFactory::getConfig();
		$userParams	= JComponentHelper::getParams('com_users');
		$db		= $this->getDbo();
		
		$db->setQuery(
			'SELECT '.$db->quoteName('id').' FROM '.$db->quoteName('#__users') .
			' WHERE '.$db->quoteName('activation').' = '.$db->Quote($token) .
			' AND '.$db->quoteName('block').' = 1' 		
		);
		$userId = (int) $db->loadResult();
		
		if (!$userId) {
			$this->setError(JText::_('COM_BT_SOCIALCONNECT_ACTIVATION_TOKEN_NOT_FOUND'));
			return false;
		}
		
		JPluginHelper::importPlugin('user');
	
		$user = JFactory::getUser($userId);
		
		if (($userParams->get('useractivation') == 2) && !$user->getParam('activate', 0))
		{
			$uri = JURI::getInstance();

			// Compile the admin notification mail values.
			$data = $user->getProperties();
			$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
			$user->set('activation', $data['activation']);
			$data['siteurl']	= JUri::base();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation'], false);
			$data['fromname'] = $config->get('fromname');
			$data['mailfrom'] = $config->get('mailfrom');
			$data['sitename'] = $config->get('sitename');
			$user->setParam('activate', 1);
			$emailSubject	= JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY',
				$data['sitename'],
				$data['name'],
				$data['email'],
				$data['username'],
				$data['siteurl'].'index.php?option=com_bt_socialconnect&task=registration.activate&token='.$data['activation']
			);

			// get all admin users
			$query = 'SELECT name, email, sendEmail, id' .
						' FROM #__users' .
						' WHERE sendEmail=1';

			$db->setQuery( $query );
			$rows = $db->loadObjectList();

			// Send mail to all users with users creating permissions and receiving system emails
			foreach( $rows as $row )
			{
				$usercreator = JFactory::getUser($id = $row->id);
				if ($usercreator->authorise('core.create', 'com_bt_socialconnect'))
				{
					$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBody);

					// Check for an error.
					if ($return !== true) {
						$this->setError(JText::_('COM_BT_SOCIALCONNECT_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
						return false;
					}
				}
			}
		}

		//Admin activation is on and admin is activating the account
		elseif (($userParams->get('useractivation') == 2) && $user->getParam('activate', 0))
		{
			$user->set('activation', '');
			$user->set('block', '0');

			$uri = JURI::getInstance();

			// Compile the user activated notification mail values.
			$data = $user->getProperties();
			$user->setParam('activate', 0);
			$data['fromname'] = $config->get('fromname');
			$data['mailfrom'] = $config->get('mailfrom');
			$data['sitename'] = $config->get('sitename');
			$data['siteurl']	= JUri::base();
			$emailSubject	= JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_BT_SOCIALCONNECT_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY',
				$data['name'],
				$data['siteurl'],
				$data['username']
			);

			$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

			// Check for an error.
			if ($return !== true) {
				$this->setError(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
				return false;
			}
		}
		else
		{
			$user->set('activation', '');
			$user->set('block', '0');
		}

		// Store the user object.
		if (!$user->save()) {
			$this->setError(JText::sprintf('COM_BT_SOCIALCONNECT_REGISTRATION_ACTIVATION_SAVE_FAILED', $user->getError()));
			return false;
		}

		return $user;
	}
	
	public function getData()
	{
		if ($this->data === null) {

			$this->data	= new stdClass();
			$app	= JFactory::getApplication();
			$params	= JComponentHelper::getParams('com_users');			
			$temp = (array)$app->getUserState('com_bt_socialconnect.registration.data', array());
			foreach ($temp as $k => $v) {
				$this->data->$k = $v;
			}			
			
			if(!isset($this->data->user_fields)){
				$this->data->user_fields = array();
			}
			
			$this->data->groups = array();
			$session = JFactory::getSession();
			$user = $session->get('btPrepareUser');	
			if($user){
				$this->data->user_fields = $user->user_fields;
			}
			
			$this->data->user_fields = Bt_SocialconnectHelper::loadUserFields($this->data->user_fields);
			
			$system	= $params->get('new_usertype', 2);

			$this->data->groups[] = $system;

			unset($this->data->password1);
			unset($this->data->password2);

			
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('user');			
			
			$results = $dispatcher->trigger('onContentPrepareData', array('com_bt_socialconnect.registration', $this->data));

			
			if (count($results) && in_array(false, $results, true)) {
				$this->setError($dispatcher->getError());
				$this->data = false;
			}
		}	
			

		return $this->data;
	}		
	
	
	
	public function getForm($data = array(), $loadData = true)
	{		
		$form = $this->loadForm('com_bt_socialconnect.registration', 'registration', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		$app = JFactory::getApplication();
		$params = $app->getParams();	
		if ($params->get('remove_user', 0)) {
			$form->removeField('username');
		}

		return $form;
	}
	
	protected function loadFormData()
	{		
		return $this->getData();
	}

	
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		$userParams	= JComponentHelper::getParams('com_bt_socialconnect');
		$session = JFactory::getSession();
		$user = $session->get('btPrepareUser');	
			
		if($user){		
			$data->name = $user->name;
			$data->username = $user->username;
			$data->email1 = $user->email1;
			$data->email2 = $user->email2;
			$data->enabled_publishing = $user->enabled_publishing;
			$data->socialId =$user->socialId;
			$data->loginType =$user->loginType;			
			$data->access_token = $user->access_token;
			
		}
		
		if ($userParams->get('site_language') == 1 && $userParams->get('frontend_userparams') == 1)
		{
			$form->loadFile('sitelang', false);
		}

		parent::preprocessForm($form, $data, $group);
	}

	
	protected function populateState()
	{
		
		$app	= JFactory::getApplication();
		$params	= $app->getParams('com_bt_socialconnect');

	
		$this->setState('params', $params);
	}	
	
	public function register($temp)
	{
	
		$config = JFactory::getConfig();
		$db		= $this->getDbo();
		$params = JComponentHelper::getParams('com_users');
		$activation =JComponentHelper::getParams('com_bt_socialconnect');
		
		$user = new JUser;
		$data = (array)$this->getData();
		
		foreach ($temp as $k => $v) {
			$data[$k] = $v;
		}

		// Prepare the data for the user object.
		if($params->get('remove_user')){
			$data['username'] = $data['email1'];
		
		}
		$data['email']		= $data['email1'];
		$data['password']	= $data['password1'];
		$data['user_id'] ='';
		$data['ip_address'] =$_SERVER['REMOTE_ADDR'];
		$data['created_time']=JFactory::getDate()->toSql();
		if(isset($data['socialId']) && isset($data['loginType'])){
			if($activation->get('ignore_activate',1)== 1){
				$useractivation =0;
			}
			else{
				$useractivation = $params->get('useractivation');
			}
		}
		else{
			$useractivation = $params->get('useractivation');
		}
		
		$sendpassword = $params->get('sendpassword', 1);
		
		if (($useractivation == 1) || ($useractivation == 2)) {
			
				$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
				$data['block'] = 1;			
		}
		else{
			$data['activation']='';
			$data['block'] = 0;
		}	
		
		$model = JModelLegacy::getInstance('Socialconnect', 'Bt_SocialconnectModel',array('ignore_request' => true));
		
		if($model->save($data)){
		$data['fromname']	= $config->get('fromname');
		$data['mailfrom']	= $config->get('mailfrom');
		$data['sitename']	= $config->get('sitename');
		$data['siteurl']	= JUri::root();

		
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
		} else {

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

		
		$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

		
		if (($params->get('useractivation') < 2) && ($params->get('mail_to_admin') == 1)) {
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
					$this->setError(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
					return false;
				}
			}
		}
		
		if ($return !== true) {
			$this->setError(JText::_('COM_BT_SOCIALCONNECT_REGISTRATION_SEND_MAIL_FAILED'));

			
			$db = JFactory::getDBO();
			$q = "SELECT id
				FROM #__users
				WHERE block = 0
				AND sendEmail = 1";
			$db->setQuery($q);
			$sendEmail = $db->loadColumn();
			if (count($sendEmail) > 0) {
			
				$jdate = new JDate();
				// Build the query to add the messages
				$q = "INSERT INTO ".$db->quoteName('#__messages')." (".$db->quoteName('user_id_from').
				", ".$db->quoteName('user_id_to').", ".$db->quoteName('date_time').
				", ".$db->quoteName('subject').", ".$db->quoteName('message').") VALUES ";
				$messages = array();

				foreach ($sendEmail as $userid) {
					$messages[] = "(".$userid.", ".$userid.", '".$jdate->toSql()."', '".JText::_('COM_BT_SOCIALCONNECT_MAIL_SEND_FAILURE_SUBJECT')."', '".JText::sprintf('COM_BT_SOCIALCONNECT_MAIL_SEND_FAILURE_BODY', $return, $data['username'])."')";
				}
				$q .= implode(',', $messages);
				$db->setQuery($q);
				$db->query();
			}
			return false;
		}
		
		if ($useractivation == 1)
			return "useractivate";
		elseif ($useractivation == 2)
			return "adminactivate";
		else
			return $user->id;
		}
	}
	
}