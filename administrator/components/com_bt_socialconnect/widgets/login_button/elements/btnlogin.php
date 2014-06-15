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


class JFormFieldBtnLogin extends JFormFieldList
{
	
	protected $type = 'BtnLogin';

	protected function getOptions()
	{
		
		$arrayField = array();
		$arrayField[] = JHtml::_('select.option', 'facebook', 'Facebook');
        $arrayField[] = JHtml::_('select.option', 'twitter', 'Twitter');        
        $arrayField[] = JHtml::_('select.option', 'google', 'Google');        
        $arrayField[] = JHtml::_('select.option', 'linkedin', 'Linkedin');        
		$options = array();					
		$options = array_merge(parent::getOptions(), $arrayField);
		return $options;
	}
	
	protected function getInput()
	{		
		$html = array();
		$attr = '';
		if(!is_array($this->value)){
			$this->value = explode(",",$this->value);
		}		
		
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';		

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .=  ' multiple="multiple"' ;
		
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';		
		$options = (array) $this->getOptions();		
		
		$html[] = JHtml::_('select.genericlist', $options, $this->name.'[]', trim($attr), 'value', 'text', $this->value, $this->id);
		
		return implode($html);
	}
}
