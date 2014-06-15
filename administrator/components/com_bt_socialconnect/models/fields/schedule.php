<?php
/**
 * @package 	bt_portfolio - BT Portfolio Component
 * @version		3.0.3
 * @created		Feb 2012
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2012 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * PortfolioCategory Form Field class for the bt_portfolio component
 */
class JFormFieldSchedule extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Schedule';
 
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	
	protected function getInput() 
	{	
		
		if($this->value){
			$this->value =(array)unserialize($this->value);
		}
		else{
			$this->value = array();
			$this->value['hour']='';
			$this->value['date']='';
		}
		
		$html ='<b><span class="textbox hasTip" title="'.JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SCHEDULE_HOUR').'::'.JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SCHEDULE_SETHOUR').'" style="float:left; margin-right:5px"> '.JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SCHEDULE_HOUR').': </span> </b>';
		$html .= '<input size="20" id="jform_hour" class="textbox" type="text" title="hour:minute" name="'.$this->name.'[hour]'.'" value="'.$this->value['hour'].'" maxlength="5"> ';
		$html .='<b><span class="textbox hasTip" title="'.JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SCHEDULE_DATE').'::'.JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SCHEDULE_SETDATE').'" style="float:left;margin-right:5px"> '.JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SCHEDULE_DATE').': </span> </b>';
		$html .= JHTML::_('calendar', $this->value['date'] , $this->name.'[date]', $this->name.'_date', '%Y-%m-%d ', '');
		
		
		return $html;
	}
	
}
