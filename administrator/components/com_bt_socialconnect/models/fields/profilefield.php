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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.html.parameter');

class JFormFieldProfilefield extends JFormField {
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	 
	protected $type = 'profilefield';	 	
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	 
	protected function getOptions()
	{
		$social = $this->element['social'] ? (string) $this->element['social'] : '';	
		$options =  array();
		switch($social){
			case 'facebook':
				$options[] = JHtml::_('select.option', '', JText::_('JNONE'));
				$options[] = JHtml::_('select.option', 'id', JText::_('ID'));
				$options[] = JHtml::_('select.option', 'name', 'Name');
				$options[] = JHtml::_('select.option', 'username', 'Username');
				$options[] = JHtml::_('select.option', 'email', 'Email');
				$options[] = JHtml::_('select.option', 'link', 'Profile link');
				$options[] = JHtml::_('select.option', 'picture', 'Profile Picture');
				$options[] = JHtml::_('select.option', 'birthday', 'Date of birth');
				$options[] = JHtml::_('select.option', 'location', 'Location');			
				$options[] = JHtml::_('select.option', 'website', 'Website');
				$options[] = JHtml::_('select.option', 'gender', 'Gender');
				$options[] = JHtml::_('select.option', 'quotes', 'Favorite Quotes');
			break;
			case 'google':
				$options[] = JHtml::_('select.option', '', JText::_('JNONE'));
				$options[] = JHtml::_('select.option', 'id', JText::_('ID'));
				$options[] = JHtml::_('select.option', 'name', 'Name');
				$options[] = JHtml::_('select.option', 'username', 'Username');
				$options[] = JHtml::_('select.option', 'email', 'Email');
				$options[] = JHtml::_('select.option', 'link', 'Profile link');
				$options[] = JHtml::_('select.option', 'location', 'Location');
				$options[] = JHtml::_('select.option', 'picture', 'Profile Picture');
				$options[] = JHtml::_('select.option', 'gender', 'Gender');
				$options[] = JHtml::_('select.option', 'birthday', 'Date of birth');
			break;
			case 'twitter':
				$options[] = JHtml::_('select.option', '', JText::_('JNONE'));
				$options[] = JHtml::_('select.option', 'id', JText::_('ID'));
				$options[] = JHtml::_('select.option', 'name', 'Name');
				$options[] = JHtml::_('select.option', 'username', 'Screen Name');
				$options[] = JHtml::_('select.option', 'email', 'Email');
				$options[] = JHtml::_('select.option', 'link', 'Profile link');
				$options[] = JHtml::_('select.option', 'picture', 'Profile Picture');
				$options[] = JHtml::_('select.option', 'birthday', 'Date of birth');
				$options[] = JHtml::_('select.option', 'location', 'Location');
				$options[] = JHtml::_('select.option', 'website', 'Website');
				$options[] = JHtml::_('select.option', 'about', 'About');
				$options[] = JHtml::_('select.option', 'quotes', 'Favorite Quotes');
			break;
			case'linkedin':
				$options[] = JHtml::_('select.option', '', JText::_('JNONE'));
				$options[] = JHtml::_('select.option', 'id', JText::_('ID'));
				$options[] = JHtml::_('select.option', 'name', 'Name');
				$options[] = JHtml::_('select.option', 'username', 'Username');
				$options[] = JHtml::_('select.option', 'email', 'Email');
				$options[] = JHtml::_('select.option', 'birthday', 'Date of birth');
				$options[] = JHtml::_('select.option', 'location', 'Location');
				$options[] = JHtml::_('select.option', 'picture', 'Profile Picture');
				$options[] = JHtml::_('select.option', 'link', 'Profile link');
				$options[] = JHtml::_('select.option', 'quotes', 'Favorite Quotes');
				$options[] = JHtml::_('select.option', 'about', 'About');
			break;
		}
		return $options;
	}
	 function getInput(){	
		
		// Initialize variables.
		$html = array();		
		
		$attr = '';
		if(!is_array($this->value)){
			$this->value = explode(",",$this->value);
		}
		// Initialize some field attributes.		
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';		

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';		
		
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';			
		$options = (array) $this->getOptions();	
		
		// Create a regular list.
		$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		
		return implode($html);
	 }
}