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

jimport('joomla.application.component.modellist');


require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';


class Bt_SocialconnectModelSocial extends JModelList

{
	protected $text_prefix = 'COM_BT_SOCIALCONNECT';	
	
	/**
	* Set state params
	*/
	protected function populateState($ordering = null, $direction = null)
	{
		
		$app = JFactory::getApplication('administrator');		
		$params	= JComponentHelper::getParams('com_bt_socialconnect');
		$this->setState('params', $params);	
	}
	/**
	* Get all folder in directory wigets
	*/	
	public function getFolder()
	{	
		$directory = "components/com_bt_socialconnect/publishes/";		
		$files = glob($directory."*");			
		$dir = array();
		foreach($files as $file)
		{		
			 if(is_dir($file))
			 {
				$tmp = new stdClass();
				$tmp->type = str_replace('components/com_bt_socialconnect/publishes/', '', $file);
				$dir[] = $tmp;  
			 }
		 }			
		return $dir;
	}
	/**
	* Get infomation in file xml in the folder publishes (example : tile ,type, version ...) 
	*/
	public function getItems()
	{
		
		$items = self::getFolder();
		$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
		$lang	= JFactory::getLanguage();
		
		foreach ($items as &$item) {			
			$path = JPath::clean($client->path.'/administrator/components/com_bt_socialconnect/publishes/'.$item->type.'/'.$item->type.'.xml');
			$item->value = $item->type;
			
			if (file_exists($path)) {
				$item->xml = simplexml_load_file($path);
			} else {
				$item->xml = null;
			}			
			$item->title	= JText::_($item->xml->name);		
			
			if (isset($item->xml) && $text = trim($item->xml->description)) {
				$item->desc = JText::_($text);
			}
			else {
				$item->desc = JText::_('COM_MODULES_NODESCRIPTION');
			}
		}
		$items = JArrayHelper::sortObjects($items, 'type', 1, true, $lang->getLocale());	
		
		return $items;
		
	}

}
