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

class Linkedin_apply_button extends BTWidget {	
	
	public static function display($params){
		$appid = JComponentHelper::getParams('com_bt_socialconnect');
		$url    = JURI::getInstance();
		$url    = $url->toString();
		$htmlCode = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"> api_key:'.$appid->get('linkappId','').' </script>';
		$htmlCode .='<div class="bt-linkedin-button-apply">';
		if($params->get('phone','')){
			$phone='data-phone="required"';
		}else{
			$phone='';
		}
		$htmlCode .= '<script type="IN/Apply" 							 
							 data-companyid="' . $params->get('company_id','') . '"
							 data-jobtitle="' . $params->get('jobtitle','') . '"
							 data-joblocation="' . $params->get('joblocation','') . '"
							 data-url="' . $url . '"
							 data-logo="' . $params->get('companylogo','') . '"
							 data-themecolor="' .'#'. $params->get('themecolor','') . '"
							 data-email="' . $params->get('rcemail','') . '"
							 '.$phone.'></script>';	
		
		$htmlCode .= '</div>';
		
		return $htmlCode;
	}
			
}

?>