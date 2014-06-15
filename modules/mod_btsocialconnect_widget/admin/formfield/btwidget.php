<?php
/**
 * @package 	BT Widget
 * @version		2.0
 * @created		February 2014

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');
class JFormFieldBtwidget extends JFormFieldList {
	protected $type = 'btwidget';
	var $options = array();	
	protected function getOptions()
	{
		$db		= JFactory::getDbo();
		$db->setQuery("SELECT c.title AS name, c.alias AS value FROM #__bt_widgets AS c WHERE published = 1  ORDER BY c.title, c.ordering ASC");
		$results = $db->loadObjectList();
			
		$this->options[] = JHtml::_('select.option', 0, 'All');
		foreach ($results as $option) {					
			$this->options[] = JHtml::_('select.option', $option->value, $option->name);				
					
		}	
		return $this->options;			
			
	}
	
}