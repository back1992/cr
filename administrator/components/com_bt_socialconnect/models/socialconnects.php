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

class Bt_SocialconnectModelSocialconnects extends JModelList
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
				'name' ,'u.name',
				'user_id', 'a.user_id'								
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
		
		$connection = $this->getUserStateFromRequest($this->context.'.filter.connection', 'filter_connection');
		$this->setState('filter.connection', $connection);	
		
		$groupId = $this->getUserStateFromRequest($this->context.'.filter.group_id', 'filter_group_id', null, 'int');
		$this->setState('filter.group_id', $groupId);		

		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);
		
		parent::populateState('u.id', 'asc');
	}	
	
	public function getItems()
	{		
		$store = $this->getStoreId();		
		if (empty($this->cache[$store]))
		{
			$groups = $this->getState('filter.groups');
			$groupId = $this->getState('filter.group_id');
			if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups)))
			{
				$items = array();
			}
			else
			{
				$items = parent::getItems();
			}
			// Bail out on an error or empty list.
			if (empty($items))
			{
				$this->cache[$store] = $items;

				return $items;
			}		
			
			$userIds = array();
			foreach ($items as $item)
			{
				$userIds[] = (int) $item->id;
				$item->group_count = 0;
				$item->group_names = '';
				$item->note_count = 0;
			}

		
			$db = $this->getDbo();
			$query = $db->getQuery(true);

			$query->select('map.user_id, COUNT(map.group_id) AS group_count')
				->from('#__user_usergroup_map AS map')
				->where('map.user_id IN ('.implode(',', $userIds).')')
				->group('map.user_id')
				// Join over the user groups table.
				->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

			$db->setQuery($query);
			$userGroups = $db->loadObjectList('user_id');

			$error = $db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);

				return false;
			}

			$query->clear()
				->select('n.user_id, COUNT(n.id) As note_count')
				->from('#__user_notes AS n')
				->where('n.user_id IN ('.implode(',', $userIds).')')
				->where('n.state >= 0')
				->group('n.user_id');

			$db->setQuery((string) $query);

			// Load the counts into an array indexed on the aro.value field (the user id).
			$userNotes = $db->loadObjectList('user_id');

			$error = $db->getErrorMsg();
			if ($error)
			{
				$this->setError($error);

				return false;
			}

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if (isset($userGroups[$item->id]))
				{
					$item->group_count = $userGroups[$item->id]->group_count;
					
					$item->group_names = $this->_getUserDisplayedGroups($item->id);
				}

				if (isset($userNotes[$item->id]))
				{
					$item->note_count = $userNotes[$item->id]->note_count;
				}
			}

			// Add the items to the internal cache.
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		// Select fields
		$query->select("u.*");
		$query->from("#__users AS u");
		
		$query->select('a.*');
		$query->join("LEFT","#__bt_users AS a ON a.user_id = u.id ");
		
		$search = $this->getState('filter.search');
		$connect = $this->getState('filter.connection');		
		$groupId = $this->getState('filter.group_id');
		// Filter by group.
		if ($groupId)
		{
			$query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = u.id');
			$query->group('u.id,u.name,u.username,u.password,u.block,u.sendEmail,u.registerDate,u.lastvisitDate,u.activation,u.params,u.email');
			if ($groupId)
			{
				$query->where('map2.group_id = '.(int) $groupId);
			}			
		}
		// Filter by connect.
		if($connect)
		{			
			$query->join('LEFT', '#__bt_connections AS c ON c.user_id = u.id');
			$social = $db->Quote('%'.$db->escape($connect, true).'%');				
			$query->where('c.social_type LIKE '.$social);		
		}			
		
		if (!empty($search)) {
			if (stripos($search, 'Id:') === 0) {
				$query->where('u.id = '.(int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0) {
				$search = $db->Quote('%'.$db->escape(substr($search, 7), true).'%');
				$query->where('(u.username LIKE '.$search.')');
			}			
			else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(u.name LIKE '.$search.' OR a.location LIKE '.$search.')');
			}
		}
	// Add the list ordering clause.		
		$orderCol	= $this->state->get('list.ordering','u.id');		
		$orderDirn	= $this->state->get('list.direction','asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}
	
	function _getUserDisplayedGroups($user_id)
	{
		$db = JFactory::getDbo();
		$sql = "SELECT title FROM ".$db->quoteName('#__usergroups')." ug left join ".
				$db->quoteName('#__user_usergroup_map')." map on (ug.id = map.group_id)".
				" WHERE map.user_id=".$user_id;

		$db->setQuery($sql);
		$result = $db->loadColumn();
		return implode("\n", $result);
	}
	
	function getTitle(){
		$db = JFactory::getDbo ();				
		$db->setQuery('SELECT * FROM #__bt_user_fields WHERE published = 1 AND show_listing = 1 order by ordering ');
		$r = $db->loadObjectList();
		return $r;
		
	}
	
	function getFieldName(){
	
		$db = JFactory::getDbo ();		
		$db->setQuery('SHOW columns FROM #__bt_users');
		$column = array();
		$r = $db->loadObjectList();	
		foreach($r As $key =>$field){
			$column[$field->Field] =$field->Default;
		}
		
		return $column;
	
	}
	

}
