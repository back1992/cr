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

class Twitter_feed extends BTWidget {	
	
	public static function display($params){		
	$embledCode = $params->get('embed_code');
	$widgetSetting = '';
	if($params->get('tweet-limit')){
		$widgetSetting .= 'data-tweet-limit="'.$params->get('tweet-limit').'" ';
	}
	if($params->get('width')){
		$widgetSetting .= 'width="'.$params->get('width').'" ';
	}
	if($params->get('height')){
		$widgetSetting .= 'height="'.$params->get('height').'" ';
	}
	if($params->get('theme')){
		$widgetSetting .= 'data-theme="'.$params->get('theme').'" ';
	}
	if($params->get('link-color')){
		$widgetSetting .= 'data-link-color="#'.$params->get('link-color').'" ';
	}
	if($params->get('border-color')){
		$widgetSetting .= 'data-border-color="#'.$params->get('border-color').'" ';
	}
	$dataChrome = array();
	if($params->get('noheader')){
		$dataChrome[] = 'noheader';
	}
	if($params->get('nofooter')){
		$dataChrome[] = 'nofooter';
	}
	if($params->get('noborder')){
		$dataChrome[] = 'noborder';
	}
	if($params->get('noscrollbar')){
		$dataChrome[] = 'noscrollbar';
	}
	if($params->get('transparent')){
		$dataChrome[] = 'transparent';
	}
	if(count($dataChrome)){
		$widgetSetting .= 'data-chrome="'.implode(' ',$dataChrome).'" ';
	}
	$identity = 'class="twitter-timeline" ';
	
	$html='<div  class="bt-twitter" >';
	$html .= str_replace(array($identity,'s-cript'), array($identity.$widgetSetting,'script'), $embledCode);
	$html .='</div>';
	return $html;
    }
			
}

?>