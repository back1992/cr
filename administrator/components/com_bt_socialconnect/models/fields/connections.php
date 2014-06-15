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
// No direct access to this file
defined('_JEXEC') or die;

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


class JFormFieldConnections extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Connections';

	
protected function getInput()
	{
		
		$html = array();
		$attr = '';
		if(!is_array($this->value)){
			$this->value = explode(",",$this->value);
		}		
		
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';		

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';		
		
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';		
		$options = array();
		$options[] = JHtml::_('select.option', 'profile_link', 'profile_link');
        $options[] = JHtml::_('select.option', 'avatar', 'avatar');    
		
		$html[] = JHtml::_('select.genericlist', $options, $this->name.'[]', trim($attr), 'value', 'text', $this->value, $this->id);
		
		return implode($html);
	}
}
