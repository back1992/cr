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

class Facebook_recommendations_bar extends BTWidget {	
	
	public static function display($params){	
	
	$fbrender	=	$params->get('rendering','xfbml');	
	$fbhref    = (!$params->get("fb_url",'')) ? JURI::current() : $params->get("fb_url",'');
	$fbtrigger =$params->get("fbtrigger",'X%');
	$fbread_time =$params->get("fbread_time","30");
	$fbflike_action =$params->get("fbflike_action","like");
	$fbside =$params->get("fbside");
	$fbnumposts	=	$params->get('fbnum_recommendations','');		
        
        // Generate code
        switch($fbrender) {            
            case 'xfbml': // XFBML
				  $html = '
					<fb:recommendations-bar 
					href="' . $fbhref . '" 
					trigger="' . $fbtrigger . '" 
					read_time="' . $fbread_time . '" 
					action="' . $fbflike_action . '" 
					side="' . $fbside . '" ';

					if($params->get("fbdomain",'')){
						$html .= ' site="' . $params->get("fbdomain") . '"';
					}
					if($params->get("fbnum_recommendations",2)){
						$html .= ' num_recommendations="' . $params->get("fbnum_recommendations",2) . '"';
					}
					if($params->get("fbmax_age")){
						$html .= ' max_age="' . $params->get("fbmax_age") . '"';
					}       
				 $html .= '></fb:recommendations-bar>';
				break;
         
            default: // HTML5
              
			    $html = '
					<div 
					class="fb-recommendations-bar"  
					data-href="'.$fbhref.'" 
					data-trigger="'.$fbtrigger.'" 
					data-read-time="'.$fbread_time.'" 
					data-action="'.$fbflike_action.'" 
					data-side="'.$fbside.'" 
				';                
				if($params->get("fbdomain")){
					$html .= ' data-site="' . $params->get("fbdomain",'') . '" ';
				}
				
				if($params->get("fbnum_recommendations",2)){
					$html .= ' data-num-recommendations="' . $params->get("fbnum_recommendations", 2) . '" ';
				}
				
				if($params->get("fbmax_age")){
					$html .= ' data-max-age="' . $params->get("fbmax_age", 0) . '" ';
				}				
				$html .= '></div>';
				break;
        }
	return $html;
	}
}	