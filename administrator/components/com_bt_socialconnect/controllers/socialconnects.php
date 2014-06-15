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
jimport('joomla.application.component.controlleradmin');

class Bt_SocialconnectControllerSocialconnects extends JControllerAdmin
{	
	public function __construct($config = array())
	{	
		parent::__construct($config);
		$this->registerTask('block',		'changeBlock');
		$this->registerTask('unblock',		'changeBlock');
	}
	
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	 
	public function getModel($name = 'Socialconnect', $prefix = 'Bt_SocialconnectModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	/**
	 * Change block user
	 */
	public function changeBlock()
	{
		$jinput = JFactory::getApplication()->input;		
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids	= $jinput->get('cid', array(), 'array');		
		$values	= array('block' => 1, 'unblock' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_BT_SOCIALCONNECT_USERS_NO_ITEM_SELECTED'));
		}
		else
		{			
			$model = $this->getModel();
			
			if (!$model->block($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				if ($value == 1)
				{
					$this->setMessage(JText::plural('COM_BT_SOCIALCONNECT_N_USERS_BLOCKED', count($ids)));
				}
				elseif ($value == 0)
				{
					$this->setMessage(JText::plural('COM_BT_SOCIALCONNECT_N_USERS_UNBLOCKED', count($ids)));
				}
			}
		}

		$this->setRedirect('index.php?option=com_bt_socialconnect&view=socialconnects');
	}
	
	public function activate()
	{
		$jinput = JFactory::getApplication()->input;
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$ids	= $jinput->get('cid', array(), 'array');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('COM_BT_SOCIALCONNECT_USERS_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Change the state of the records.
			if (!$model->activate($ids))
			{
				JError::raiseWarning(500, $model->getError());
			}
			else
			{
				$this->setMessage(JText::plural('COM_BT_SOCIALCONNECT_N_USERS_ACTIVATED', count($ids)));
			}
		}

		$this->setRedirect('index.php?option=com_bt_socialconnect&view=socialconnects');
	}
}
