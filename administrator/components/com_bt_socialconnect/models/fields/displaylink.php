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

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldDisplayLink extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected	$type = 'displaylink';
	
	public static function getToken($prefix, $name, $params=null)
	{
		if(is_null($params)){
			$plugin = JPluginHelper::getPlugin('system', 'bt_cronjob');
			$params = new JRegistry($plugin->params);
		}

		$token = trim( $params->get($name) );
		$config =  JFactory::getConfig();
		$email = $config->get('config.mailfrom');
		$db = $config->get('config.db');

		return JFilterOutput::stringURLSafe(md5($prefix. $email. $db. $token));
	}

	protected function getInput()
	{
		$db = JFactory::getDbo();
		static $styleoutput = null;
		$query = $db->getQuery(true);
		$query->select('params');
		$query->from('#__extensions');
		$query->where('type = '. $db->Quote('component'). ' AND element = '. $db->Quote('com_bt_socialconnect'));
		$db->setQuery($query);
		$params = new JRegistry($db->loadResult());		
		$task = $params->get('task'. $this->element['name']);	

		$return = '';
		if(empty($task)){
			return '<span class="value red enabled_cronjobs_1">'. JText::_('COM_BT_SOCIALCONNECT_SECRET_KEY_IS_NOT_REGISTERED'). '</span>';
		} 
		$token = $this->getToken('bt_cronjob'. $this->element['name'], 'task'. $this->element['name'], $params);
		$link = JURI::root(). 'index.php?task=cron&'. $token. '=1';
		
		return '<span class="enabled_cronjobs_1"><a href="'. $link. '" target="_blank">'. $link. '</a></span>';

	}
}
