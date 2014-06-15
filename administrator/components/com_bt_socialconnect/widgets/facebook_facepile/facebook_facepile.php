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

class Facebook_facepile extends BTWidget {	
	
	public static function display($params){		
	$fbrendering	=	$params->get('fbrendering','');
	$fburl	=	$params->get('fburl','');	
	$fbaction =$params->get("fbaction","");
	$fbnum_row =$params->get("fbnum_row",30);
	$fbsize =$params->get("fbsize","medium");
	$fbshowcount	=	$params->get('fbshowcount','true');
	$fbwidth	=	$params->get('fbwidth','300');
	$fbcolorscheme =	$params->get('fbcolorscheme','light');
	
	switch($fbrendering){
		case'html5':
			$html = '<div 
					class="fb-facepile"
					data-max-rows="'.$fbnum_row.'" 
					data-size="'.$fbsize.'" 
					data-width="'.$fbwidth.'" 
					data-show-count="'.$fbshowcount.'" 
					data-colorscheme="'.$fbcolorscheme.'"
										
				';
				if($fburl){
					$html .= ' data-href="' . $fburl . '" ';
				}				
				if($fbaction){
					$html .= ' data-action="' . $fbaction . '" ';
				}
				
				$html .= '></div>';				
			break;
		default:
			$html = '
					<fb:facepile
					href="' . $fburl . '" 					
					action="' . $fbaction . '" 
					max_rows="' . $fbnum_row . '" 
					size="' . $fbsize . '" 
					show_count="' . $fbshowcount . '" 
					width="' . $fbwidth . '" 
					colorscheme="' . $fbcolorscheme . '" ';						
			
			$html .= '></fb:facepile>';
			break;
	}
		return $html;
	}
			
}

?>