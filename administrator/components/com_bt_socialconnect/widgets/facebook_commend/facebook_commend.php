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

class Facebook_commend extends BTWidget {	
	
	public static function display($params){		
	$url    = JURI::getInstance();
	$fb_url    = $url->toString();
	$fbrendering	=	$params->get('fbrendering','html5');	
	$fbwidth =$params->get("fbwidth");
	$fbnumber_post =$params->get("fbnumber_post");
	$fbcolorscheme =$params->get("fbcolorscheme","light");
	$fborder_by =$params->get("fborder_by","social");

	switch($fbrendering){
	case'html5':
		$html = '	<div 
					class="fb-comments"  
					data-href="'.$fb_url.'" 
					data-width="'.$fbwidth.'" 
					data-num-posts="'.$fbnumber_post.'" 
					data-colorscheme="'.$fbcolorscheme.'" 
					data-order-by="'.$fborder_by.'">
					</div>					
				';
		break;
	default:
		$html = '<fb:comments
					href="' . $fb_url . '" 
					width="' . $fbwidth . '" 
					num_posts="' . $fbnumber_post . '" 
					colorscheme="' . $fbcolorscheme . '" 					 
					order_by="' . $fborder_by . '" >';
		$html .='</fb:comments>';
				
		
		break;
		
	}
	return $html;
	}
			
}

?>