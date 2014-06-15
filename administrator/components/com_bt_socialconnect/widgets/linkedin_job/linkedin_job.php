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
 
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/btwidget.php');

class Linkedin_job extends BTWidget {	
	
	public static function display($params){
		$htmlCode = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
		$htmlCode .='<div class="bt-linkedincompanyprofile">';
		if($params->get('job',0)){
			$config='data-companyid="'.$params->get('company_id','').'"';
		}else{
			$config='';
		}
		$htmlCode .='<script type="IN/JYMBII" 
		'.$config.'
		data-format="inline"></script>';
		
		
		$htmlCode .= '</div>';
		
		return $htmlCode;
	}
			
}

?>