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


class Bt_SocialconnectHelper
{
	public static $extension = 'com_bt_socialconnect';
	 
	 /**
     * Configure the Linkbar.
     */
	
	public static function addSubmenu($vName)
	{		
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_CPANEL'), 
			'index.php?option=com_bt_socialconnect&view=cpanel', 
			$vName == 'cpanel');			
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_USER_MANAGER'),
			'index.php?option=com_bt_socialconnect&view=socialconnects',
			$vName == 'socialconnects'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_USER_FIELD_MANAGER'),
			'index.php?option=com_bt_socialconnect&view=userfields',
			$vName == 'userfields'
		);			
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_SOCIALPUBLISHS_MANAGER'),
			'index.php?option=com_bt_socialconnect&view=channels',
			$vName == 'channels');
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_WIDGET_MANAGER'),
			'index.php?option=com_bt_socialconnect&view=widgets',
			$vName == 'widgets');
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_MESSAGES_MANAGER'),
			'index.php?option=com_bt_socialconnect&view=messages',
			$vName == 'messages');
		JSubMenuHelper::addEntry(
			JText::_('COM_BT_SOCIALCONNECT_CONNECTION_STATISTICS'),
			'index.php?option=com_bt_socialconnect&view=statistics',
			$vName == 'statistics'
		);
	
	}
	
	public static function getActions($categoryId = 0, $socialconnectId = 0)
	{
		// Reverted a change for version 2.5.6
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($socialconnectId) && empty($categoryId)) {
			$assetName = 'com_bt_socialconnect';
		}
		elseif (empty($socialconnectId)) {
			$assetName = 'com_bt_socialconnect.category.'.(int) $categoryId;
		}
		else {
			$assetName = 'com_bt_socialconnect.socialconnect.'.(int) $socialconnectId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}

	 public static function addAdminScript() {
        $checkJqueryLoaded = false;
        $document = JFactory::getDocument();
        $header = $document->getHeadData();
        JHTML::_('behavior.framework');
		if (!version_compare(JVERSION, '3.0', 'ge')) {
			foreach ($header['scripts'] as $scriptName => $scriptData) {
				if (substr_count($scriptName, '/jquery')) {
					$checkJqueryLoaded = true;
				}
			}

			//Add js
			if (!$checkJqueryLoaded)
			$document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/jquery.min.js');	
		}
		$document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/default.js');		
        $document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/menu.js');		
        $document->addScript(JURI::root() . 'administrator/components/com_bt_socialconnect/assets/js/bt_social.js');		
        $document->addScriptDeclaration('jQuery.noConflict();');
        $document->addStyleSheet(JURI::root() . 'components/com_bt_socialconnect/assets/icon/admin.css');        
		$document->addStyleSheet(JURI::root() . 'components/com_bt_socialconnect/assets/css/legacy.css');
		$document->addStyleSheet(JURI::root() . 'administrator/components/com_bt_socialconnect/assets/css/bt_social.css');
		if (!Bt_SocialconnectLegacyHelper::isLegacy()) {	
            JHtml::_('formbehavior.chosen', 'select');
        }
    }
	
	
	public static function getGroups()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
			' FROM #__usergroups AS a' .
			' LEFT JOIN '.$db->quoteName('#__usergroups').' AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' GROUP BY a.id, a.title, a.lft, a.rgt' .
			' ORDER BY a.lft ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}
		foreach ($options as &$option)
		{
			$option->text = str_repeat('- ', $option->level).$option->text;
		}
		return $options;
	}
	
	public static function getPublishedOptions() {
        // Build the filter options.
        $options = array();
        $options[] = JHtml::_('select.option', '1', JText::_('JPUBLISHED'));
        $options[] = JHtml::_('select.option', '0', JText::_('JUNPUBLISHED'));
        return $options;
    }
	
	public static function getConnections()
	{
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT c.social_type as value,c.social_type AS text' .
			' FROM #__bt_connections AS c' 			
		);
		$options = $db->loadObjectList();
		foreach ($options as &$option)
		{
			$option->text = ' '.$option->text;
		}
		
		return $options;
	}
	
	public static function getTriggerOptions(){
		// Build the filter options.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT m.trigger as value,m.trigger AS text' .
			' FROM #__bt_messages AS m' 			
		);
		$options = $db->loadObjectList();
		foreach ($options as &$option)
		{
			$option->text = ' '.$option->text;
		}
		
		return $options;	
	}
	
	public static function getStatusOptions(){
		$option =array();
		$option[]=JHTML::_('select.option', '1', 'submitted');
		$option[]=JHTML::_('select.option', '2', 'pendding');
		$option[]=JHTML::_('select.option', '0', 'error');			
				
		return $option;
	}
	public static function getComponentOptions(){
		$option =array();
		$option[]=JHTML::_('select.option', 'content', 'Joomla Content');
		if(is_dir(JPATH_ROOT . '/components/com_k2')){
			$option[]=JHTML::_('select.option', 'k2', 'K2');
		}
		if(is_dir(JPATH_ROOT . '/components/com_docman')){
			$option[]=JHTML::_('select.option', 'docman', 'Docman');
		}
		if(is_dir(JPATH_ROOT . '/components/com_easydiscuss')){
			$option[]=JHTML::_('select.option', 'easydiscuss', 'Easydiscuss');
		}
		if(is_dir(JPATH_ROOT . '/components/com_easyblog')){
			$option[]=JHTML::_('select.option', 'easyblog', 'Easyblog');
		}
		if(is_dir(JPATH_ROOT . '/components/com_kunena')){
			$option[]=JHTML::_('select.option', 'kunena', 'Kunena');	
		}
		if(is_dir(JPATH_ROOT . '/components/com_virtuemart')){
			$option[]=JHTML::_('select.option', 'virtuemart', 'Virtuemart');
		}
		if(is_dir(JPATH_ROOT . '/components/com_jreviews')){
			$option[]=JHTML::_('select.option', 'jreview', 'Jreview');	
		}
		if(is_dir(JPATH_ROOT . '/components/com_sobipro')){
			$option[]=JHTML::_('select.option', 'sobipro', 'Sobipro');	
		}				
		return $option;
	}
	public static function getSocialpost(){
		$option =array();
		$option[]=JHTML::_('select.option', 'profile', 'user');
		$option[]=JHTML::_('select.option', 'system', 'system');				
		return $option;
	}
	
	public static function getWidgettype(){
		// Build the filter options.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT w.wgtype as value,w.wgtype AS text' .
			' FROM #__bt_widgets AS w' 			
		);
		$options = $db->loadObjectList();
		foreach ($options as &$option)
		{
			$option->text = ' '.$option->text;
		}
		
		return $options;
	
	}
	
	public static function getSocialtype(){
		// Build the filter options.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT m.type as value,m.type AS text' .
			' FROM #__bt_messages AS m' 			
		);
		$options = $db->loadObjectList();
		foreach ($options as &$option)
		{
			$option->text = ' '.$option->text;
		}
		
		return $options;	
	}
	
	public static function getUserfieldType(){
		// Build the filter options.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT f.type as value,f.type AS text' .
			' FROM #__bt_user_fields AS f' 			
		);
		$options = $db->loadObjectList();
		foreach ($options as &$option)
		{
			$option->text = ' '.$option->text;
		}
		
		return $options;	
	}
	
	public static function getPublishtype(){
		// Build the filter options.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT p.type as value,p.type AS text' .
			' FROM #__bt_channels AS p' 			
		);
		$options = $db->loadObjectList();
		foreach ($options as &$option)
		{
			$option->text = ' '.$option->text;
		}
		
		return $options;
	
	}

	

	
}
