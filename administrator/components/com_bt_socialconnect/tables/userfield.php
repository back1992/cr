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
/**
 * Userfield Table class
 */

class Bt_SocialconnectTableUserfield extends JTable
{

	function __construct(&$db)
	{		
		parent::__construct('#__bt_user_fields', 'id', $db);
	}
	/**
	 * Stores a Userfield
	 *
	 * @param	boolean	True to update fields even if they are null.
	 * @return	boolean	True on success, false on failure.
	 * @since	1.6
	 */	
	public function store($updateNulls = false)
	{			
		unset($this->type_string);			
		unset($this->type_text);			
		unset($this->type_dropdown);
		unset($this->type_date);
		unset($this->type_image);
		$table = JTable::getInstance('Userfield', 'Bt_SocialconnectTable');
		if ($table->load(array('alias' => $this->alias, 'name' => $this->name))
			&& ($table->id != $this->id || $this->id == 0))
		{

			$this->setError(JText::_('COM_BT_SOCIALCONNECT_ERROR_UNIQUE_ALIAS'));

			return false;
		}
		return parent::store($updateNulls);
	}
	
	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) 
		{
			
			$this->{'type_'.$this->type} = $this->default_values;				
			
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	function check()
	{
		jimport('joomla.filter.output');
	
		$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);
		
		if (empty($this->ordering)) {			
			$this->ordering = self::getNextOrder();
		}
		
		if (empty($this->alias)) {
				$this->alias = $this->name;
			}
			$this->alias = JApplication::stringURLSafe($this->alias);
			if (trim(str_replace('1', '', $this->alias)) == '') {
				$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
			}
		$this->alias=str_replace("-","_",$this->alias);	
			
		if($this->type =='0') return false;
		
		if(is_array($_REQUEST['jform']['type_'.$this->type])){		
			$_REQUEST['jform']['type_'.$this->type]['value'] = $this->remove_array_empty_values($_REQUEST['jform']['type_'.$this->type]['value']);			
			$_REQUEST['jform']['type_'.$this->type] = serialize($_REQUEST['jform']['type_'.$this->type]);
		}
		
		if($this->type!='image'){		
			$this->default_values = $_REQUEST['jform']['type_'.$this->type];
		}

		return true;
	}
	
	public function bind($array, $ignore = '')
	{		
		
		 return parent::bind($array, $ignore);
	}
	/*
	* Remove array if value in array empty 
	*/
	public static function remove_array_empty_values($array)
	{
			$new_array = array();			 
			$null_exceptions = array();			 
			foreach ($array as $key => $value)
			{
				$value = trim($value);		
			 
				if(!in_array($value, $null_exceptions) && $value != "")
				{
				$new_array[] = $value;
				}
			}
			return $new_array;
	}
}
