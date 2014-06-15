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

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldPublishOrdering extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	 
	protected $type = 'PublishOrdering';

	
	protected function getInput()
	{
		
		$html = array();
		$attr = '';
		// Initialize some field attributes.	
		
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';		
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		
		$id	= (int) $this->form->getValue('id');		
		
		$query = 'SELECT ordering AS value, type AS text' .
				' FROM #__bt_channels'.
				' ORDER BY ordering';
		
		
		if ((string) $this->element['readonly'] == 'true') {
			$html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $id ? 0 : 1);
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		// Create a regular list.
		else {
			$html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, $id ? 0 : 1);
		}

		return implode($html);
	}
}
