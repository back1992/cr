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

class Facebook_activityfeed extends BTWidget {	
	
	public static function display($params){
	
	if($params->get("fbdomain")!=''){
	$domain =$params->get("fbdomain");
	}
	else{
	$domain =JURI::root();
	}

	switch ($params->get("fbRendering",0)) { 
		
		case 1: // XFBML		
			$html = '
			<fb:activity  
			site="' .$domain .'"  
			app_id="'.$params->get('fbappid') .'"  
			action="'. $params->get('fbaction') .'"  
			width="'. $params->get('fbwidth') .'"  
			height="'. $params->get('fbheight') .'" 
			header="'. $params->get('fbshowheader') .'"  
			linktarget="'. $params->get('fblinktarget') .'" 
			font="'. $params->get('fbfont') .'" 
			colorscheme="'. $params->get('fbcolorscheme') .'" 
			recommendations="'. $params->get('fbrecommendations') .'" 
			border_color="'. $params->get('fbbordercolor') .'" ' ;		 
			  
			$html .='></fb:activity>';
			break; // END XFBML

		case 2: // HTML5

			$html = '
			<div class="fb-activity" 
			data-site="' .$domain .'" 
			data-app-id="'.$params->get('fbappid') .'" 
			data-action="'. $params->get('fbaction') .'" 
			data-width="'. $params->get('fbwidth') .'" 
			data-height="'. $params->get('fbheight') .'" 
			data-header="'. $params->get('fbshowheader') .'" 
			data-linktarget="'. $params->get('fblinktarget') .'" 
			data-colorscheme="'. $params->get('fbcolorscheme') .'" 
			data-border-color="'. $params->get('fbbordercolor') .'" 
			data-font="'. $params->get('fbfont') .'" 
			data-recommendations="'. $params->get('fbrecommendations') .'" ' ;	 
			  
			$html .='></div>';

			break; // END HTML5
	  
		default:  			
			break;

	}
	return $html;
	
	}
			
}

?>