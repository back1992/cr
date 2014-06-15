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

class Facebook_recommendations_box extends BTWidget {	
	
	public static function display($params){	

	$fbrendering	=	$params->get('fbrendering','html5');
	if($params->get('fbdomain','')!=''){
		$fbdomain	=	$params->get('fbdomain','');
	}else{
		$fbdomain =JURI::root();
	}
	$fbapp_id =$params->get("fbapp_id");
	$fbaction =$params->get("fbaction");
	$fbwidth =$params->get("fbwidth","300");
	$fbheight =$params->get("fbheight","300");
	$fbshowheader	=	$params->get('fbshowheader','');
	$fbcolorscheme	=	$params->get('fbcolorscheme','400');
	$fblinktarget =	$params->get('fblinktarget','_blank');	
	$fbfont =	$params->get('fbfont','');	
    switch($fbrendering) {
            
            case 'xfbml': // XFBML
				  $html = '
					<fb:recommendations					
					action="' . $fbaction . '" 
					width="' . $fbwidth . '" 
					height="' . $fbheight . '" 
					fbshowheader="' . $fbshowheader . '" 
					colorscheme="' . $fbcolorscheme . '" 
					linktarget="' . $fblinktarget . '" 
					font="' . $fbfont . '" ';

					if($params->get("fbdomain")){
						$html .= ' site="' . $params->get("fbdomain") . '"';
					}					
					if($params->get("fbmax_age")){
						$html .= ' max_age="' . $params->get("fbmax_age") . '"';
					}       
				$html .= '></fb:recommendations-bar>';
				break;
         
            default: // HTML5
              
			    $html = '
					<div 
					class="fb-recommendations"  
					data-site="'.$fbdomain.'" 
					data-width="'.$fbwidth.'" 
					data-height="'.$fbheight.'" 
					data-header="'.$fbshowheader.'" 
					data-action="'.$fbaction.'" 
					data-linktarget="'.$fblinktarget.'" 
					data-colorscheme="'.$fbcolorscheme.'" 					
				';
                
				if($params->get("fbfont")){
					$html .= ' data-font="' . $params->get("fbfont") . '" ';
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