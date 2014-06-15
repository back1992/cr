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
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');


class Bt_SocialconnectModelWidgets extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'w.id',				
				'title', 'w.title',
				'alias', 'w.alias',				
				'wgtype', 'w.wgtype',
				'published', 'w.published',
				'ordering', 'w.ordering',					
				'note', 'w.note',				
				'updated_time', 'w.updated_time',							
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{		
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		
		$jinput = new Jinput;
		if ($layout = $jinput->get('layout')) {
			$this->context .= '.'.$layout;
		}		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published= $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $published);		
		
		$widgettype = $this->getUserStateFromRequest($this->context.'.filter.widgettype', 'filter_widgettype');
		$this->setState('filter.widgettype', $widgettype);		
		
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		$this->setState('params', $params);
		
		 parent::populateState('w.title', 'asc');
	}	
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	 
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select("w.*");
		$query->from("#__bt_widgets AS w");		
		
		$search = $this->getState('filter.search');		
		
		if (!empty($search)) {
		
			if (stripos($search, 'id:') === 0) {
				$query->where('w.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(w.title LIKE '.$search.' OR w.wgtype LIKE '.$search.')');
			}			
		}
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('w.published = '.(int) $published);
		}
		// Filter by widget type
		$widgettype = $this->getState('filter.widgettype');		
		if (!empty($widgettype)) {
			$query->where('w.wgtype = "'.$widgettype.'"');		
		}		
		
		$query->group('w.id');
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'w.ordering') {
			$orderCol = 'w.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}
	
	public function getShowItems(){	
	
		$widgettype = $this->getState('filter.widgettype');
		$db = JFactory::getDBO();
		
		if (!empty($widgettype)) {
			$db->setQuery("SELECT * FROM #__bt_widgets WHERE published =1 AND  wgtype ='".$widgettype."'");	
		}else{
			$db->setQuery("SELECT * FROM #__bt_widgets WHERE published =1 ");
		}
		$rows = $db->loadObjectList();
		
		return $rows;
	}
	
	/**
	 * Method to get folder in path.
	 *
	 * @return	folder	An directory
	 */
	public function getFolder()
	{	
		$directory = "components/com_bt_socialconnect/widgets/";		
		$files = glob($directory."*");			
		$dir = array();
		foreach($files as $file)
		{		
			 if(is_dir($file))
			 {
				$tmp = new stdClass();
				$tmp->wgtype = str_replace('components/com_bt_socialconnect/widgets/', '', $file);
				$dir[] = $tmp;
			 }
		 }			
		return $dir;
	}
	
	/**
	 * Method to get value in filde xml.
	 *
	 * @return	value 
	 */
	public function getItemFolder()
	{
		
		$items = self::getFolder();		
		$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
		$lang	= JFactory::getLanguage();
		
		foreach ($items as &$item) {			
			$path = JPath::clean($client->path.'/administrator/components/com_bt_socialconnect/widgets/'.$item->wgtype.'/'.$item->wgtype.'.xml');
			$item->type = $item->wgtype;
			
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
		$items = JArrayHelper::sortObjects($items, 'wgtype', 1, true, $lang->getLocale());	
		
		return $items;
		
	}

}
