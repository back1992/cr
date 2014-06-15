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
// No direct access

defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class Bt_SocialconnectControllerSocialconnect extends JControllerForm
{
	
	function __construct($config = array())
	{		

		parent::__construct($config);
	}
	
	protected function allowAdd($data = array())
	{		
		$user = JFactory::getUser();
		
		$allow = null;		

		if ($allow === null)
		{		
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}
	
	protected function allowEdit($data = array(), $key = 'id')
	{
	
		if (JAccess::check($data[$key], 'core.admin'))
		{			
			if (!JFactory::getUser()->authorise('core.admin'))
			{
				return false;
			}
		}

		return parent::allowEdit($data, $key);
	}
	
 /**
 * Controller save users set user_fields in data
 */
	public function save($key = null, $urlVar = 'id')
	{
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->post->get('jform', array(), 'array');
		$data['user_id'] =$data['id'];
		$user_fields = $jinput->post->get('user_fields', array(), 'array'); 
		$data['user_fields'] = $user_fields;			
		JRequest::setVar('jform', $data, 'post');		
		$jinput->post->set('jform', $data);
		if (isset($data['password']) && isset($data['password2']))
		{			
			if ($data['password'] != $data['password2'])
			{			
				$this->setMessage(JText::_('JLIB_USER_ERROR_PASSWORD_NOT_MATCH'), 'warning');
				$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnect&layout=edit', false));
			}

			unset($data['password2']);
		}
		return parent::save($key,$urlVar);
	}
	
	
}
