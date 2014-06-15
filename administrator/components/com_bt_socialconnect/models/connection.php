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
jimport('joomla.application.component.modeladmin');
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
/**
 * Connection Model
 */
class Bt_SocialconnectModelConnection extends JModelAdmin
{
	protected $text_prefix = 'COM_BT_SOCIALCONNECT';	
	protected function canDelete($record)
	{
      if (!empty($record->id))
         return parent::canDelete($record);
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_bt_socialconnect.connection.'.(int) $record->id);
		}		
		// Default to component settings if neither article nor category known.
		else {
			return parent::canEditState('com_bt_socialconnect');
		}
	}
	
	public function getTable($type = 'Connection', $prefix = 'Bt_SocialconnectTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}	
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_bt_socialconnect.connection', 'connection', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}				

		return $form;
	}	
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_bt_socialconnect.edit.connection.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			
		}		

		return $data;
	}	
	
	public  function save($data){
		$db =  $this->getDbo();
		return parent::save($data);
	}
	
	public function delete(&$pks){
		$db =  $this->getDbo();	
		return	parent::delete($pks);		
	}


}
