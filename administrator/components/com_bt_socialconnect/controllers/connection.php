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

/**
 * Connection controller class.
 */
class Bt_SocialconnectControllerConnection extends JControllerForm
{

	function __construct($config = array())
	{	
		parent::__construct($config);
	}

	
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$jinput = JFactory::getApplication()->input;
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid',(int) $jinput->getInt('filter_category_id'), 'int');
		$allow = null;		

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_bt_socialconnect.category.' . $categoryId);
		}
		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}
	
	protected function allowEdit($data = array(), $key = 'id')
	{	
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');
		
		if ($user->authorise('core.edit', 'com_bt_socialconnect.connection.' . $recordId))
		{
			return true;
		}
		if ($user->authorise('core.edit.own', 'com_bt_socialconnect.connection.' . $recordId))
		{			
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}
				$ownerId = $record->created_by;
			}
		
			if ($ownerId == $userId)
			{
				return true;
			}
		}
		
		return parent::allowEdit($data, $key);
	}
	
/**
* Delete connection from user connection
*/
	
	public function delete(){
	
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
		$model	= $this->getModel('Connection', 'Bt_SocialconnectModel');
		$id =  $jinput->get('id', null, 'ARRAY');	
		$socialID	= $jinput->getInt('socialId');	
		$return = $model->delete($id);
		if($return){						
			
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnect&layout=edit&id='.$socialID, false));
		}else{
			
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnect&layout=edit&id='.$socialID, false));
			return false;
		}
	}
	
	
}
