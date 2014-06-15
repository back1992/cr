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

class Linkedin_company_insider extends BTWidget {	
	
	public static function display($params){
		$htmlCode = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
		$htmlCode .='<div class="bt-linkedincompany">';
		$htmlCode .= '<script type="IN/CompanyInsider" 							 
							 data-id="' . $params->get('company_id','') . '"
							 data-modules="'.implode(',',$params->get('view','')) . '"></script>';	
		
		$htmlCode .= '</div>';
		
		return $htmlCode;
	}
			
}

?>