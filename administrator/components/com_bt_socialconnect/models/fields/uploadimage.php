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


class JFormFieldUploadimage extends JFormFieldList
{
	
	protected $type = 'Uploadimage';
	
protected function getInput()
	{
	
			if($this->value!=''){
				$avatar='<img src="'. JURI::root().'images/bt_socialconnect/avatar/'.$this->value.'"/>';
				$html ='<div class=\'imageupload\'>';
				$html .='<span class="editlinktip hasTip" title="'. htmlspecialchars($avatar).'">';
				$html .='<img src='. JURI::root().'images/bt_socialconnect/avatar/'.$this->value.'   style="height:30px"  />';	
				$html .='</span>';
				$html .= '<input type="file" name="upload_image"  class="inputbox" size="22"/>';			
				$html .='</div>';
			}
			else{
				$html ='<div class=\'inputupload\'>';
				$html .= '<input type="file" name="upload_image" style="width:230px"  class="inputbox" size="30"/>';
				$html .='</div>';
			}
		return $html;
	}
}
