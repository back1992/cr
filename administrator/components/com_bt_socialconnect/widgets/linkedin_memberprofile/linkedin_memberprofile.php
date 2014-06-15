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

class Linkedin_memberprofile extends BTWidget {	
	
	public static function display($params){
		$htmlCode = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
		$htmlCode .='<div class="bt-linkedinmemberprofile">';
		switch($params->get('layout','inline')){
		case'inline':
			$htmlCode .= '<script type="IN/MemberProfile" 							 
							 data-id="' . $params->get('profile_url','') . '"
							 data-format="inline"
							 data-related="'.$params->get('connection',false).'"></script>';
			
			break;
		case'iconname':
			$htmlCode .= '<script type="IN/MemberProfile" 							 
							 data-id="' . $params->get('profile_url','') . '"
							 data-format="'. $params->get('event','') . '"
							 data-text ="'.$params->get('textname','').'"
							 data-related="'.$params->get('connection',false).'"></script>';
			break;
		case'icon':
			$htmlCode .= '<script type="IN/MemberProfile" 							 
							 data-id="' . $params->get('profile_url','') . '"
							 data-format="'. $params->get('event','') . '"							
							 data-related="'.$params->get('connection',false).'"></script>';
			break;
		}
		
		
		$htmlCode .= '</div>';
		
		return $htmlCode;
	}
			
}

?>