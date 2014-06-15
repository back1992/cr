<?php
/**
 * @package 	formfield
 * @version		2.0
 * @created		Oct 2011

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2012 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html');
jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

class JFormFieldChannel extends JFormFieldList {
	protected $type = 'Channel'; //the form field type
    var $options = array();

    protected function getOptions() {

		    
			$db = JFactory::getDBO();			
			
			$db->setQuery("SELECT c.id AS id ,c.title AS title, c.alias AS alias, c.type AS type FROM #__bt_channels AS c WHERE published = 1  ORDER BY c.title");
			
			$results = $db->loadObjectList();			
			if(count($results)){				
				
				$this->options[] = JHtml::_('select.option', 0, 'All');
				foreach ($results as $option) {					
						$this->options[] = JHtml::_('select.option', $option->id, $option->title);				
					
				}	
				return $this->options;
			}
		
        return $this->options;
		
	}  

    
}
