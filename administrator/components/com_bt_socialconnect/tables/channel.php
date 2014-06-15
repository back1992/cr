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
 * Channel Table class
 */

class Bt_SocialconnectTableChannel extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{		
		$this->created = JFactory::getDate()->toSQL();	
		parent::__construct('#__bt_channels', 'id', $db);
	}
	
	/**
	 * Overloaded bind function
	 *
	 * @param       array           named array
	 * @return      null|string     null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	 
	public function bind($array, $ignore = '')
	 {	 
	  if (isset($array['params']) && is_array($array['params']))
	  {
	   $registry = new JRegistry;
	   $registry->loadArray($array['params']);
	   $array['params'] = (string) $registry;
	  }

	  return parent::bind($array, $ignore);
	 }
	
	public function check()
	{		
			
			if (trim($this->title) == '') {
				$this->setError(JText::_('COM_BT_SOCIALCONNECT_WARNING_PROVIDE_VALID_NAME'));
				return false;
			}
			if (empty($this->ordering)) {			
				$this->ordering = self::getNextOrder();
			}			
			if (empty($this->alias)) {
				$this->alias = $this->title;
			}
			$this->alias = JApplication::stringURLSafe($this->alias);
			if (trim(str_replace('-', '', $this->alias)) == '') {
				$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
			}
		return true;
	}
		
	/**
	 * Stores a channel
	 *
	 * @param	boolean	True to update fields even if they are null.
	 * @return	boolean	True on success, false on failure.
	 * @since	1.6
	 */	
	 
	public function store($updateNulls = false)
	{

		
		$table = JTable::getInstance('Channel', 'Bt_SocialconnectTable');		
		if ($table->load(array('alias' => $this->alias, 'title' => $this->title))
			&& ($table->id != $this->id || $this->id == 0))
		{

			$this->setError(JText::_('COM_BT_SOCIALCONNECT_ERROR_UNIQUE_ALIAS'));

			return false;
		}
		return parent::store($updateNulls);
		
	}

	
}
