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

class Google_badge extends BTWidget {	
	
	public static function display($params){		
	$page_id = $params->get('page_id',116012467976208531641);	
	$badge_size = $params->get('badge_size','badge');
	$badge_tag = $params->get('badge_tag','htmlvalid');
	$theme = $params->get('theme','light');
	$width = $params->get('width',300);
	$height = $params->get('height',69);
	$parse_tags = $params->get('parse_tags','onload');
	$customize_name = $params->get('customize_name','Home Of Science');
	$alt_icon = $params->get('alt_icon','');
	$async = $params->get('async',1);	
	$document = JFactory::getDocument();
	
	if($parse_tags == 'explicit'){
	$explicit='<script type="text/javascript">gapi.plus.go();</script>';
	} 
	elseif($parse_tags == 'onload') {
	$explicit='';
	}	
	if ($async =='0' AND $parse_tags=='onload'){
	$script ='<link href="https://plus.google.com/'.$page_id.'" rel="publisher" /><script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: "en-GB"}</script>';
	}elseif($async =='1' AND $parse_tags=='onload'){
	$script='<link href="https://plus.google.com/'.$page_id.'" rel="publisher" /><script type="text/javascript">window.___gcfg = {lang: "en-GB"};(function() {var po = document.createElement("script");po.type = "text/javascript";po.async = true;po.src = "https://apis.google.com/js/plusone.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(po, s);})();</script>';
	}elseif($async =='0' OR $async =='1' AND $parse_tags=='explicit'){
	$script ='<link href="https://plus.google.com/'.$page_id.'" rel="publisher" /><script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: "en-GB"}</script>';
	}
	$document->addCustomTag($script);
	if($badge_tag =='default'){
		switch ($badge_size) {
		
		case 'badge':
			$html = "<g:plus height='".$height."' width='".$width."' theme='".$theme."' href='https://plus.google.com/".$page_id."' size='badge'></g:plus>".$explicit;
			break;
		case 'smallbadge':
			$html = "<g:plus height='".$height."' width='".$width."' theme='".$theme."' href='https://plus.google.com/".$page_id."' size='smallbadge'></g:plus>".$explicit;
			break;		
		case 'smallicon':
			$html = "<a href='https://plus.google.com/".$page_id."?prsrc=3' style='cursor:pointer;display:inline-block;text-decoration:none;color:#333;font:13px/16px arial,sans-serif;'><span style='display:inline-block;font-weight:bold;vertical-align:top;margin-right:5px;'>".$customize_name."</span><span style='display:inline-block;vertical-align:top;margin-right:13px;'>".JTEXT::_('on')."</span><img src='https://ssl.gstatic.com/images/icons/gplus-16.png' alt='".$alt_icon."' style=\"border:0;width:16px;height:16px;\"/></a>".$explicit;
			break;
		case 'mediumicon':
			$html = "<a href='https://plus.google.com/".$page_id."?prsrc=3' style='cursor:pointer;display:inline-block;text-decoration:none;color:#333;font:13px/16px arial,sans-serif;'><span style='display:inline-block;font-weight:bold;vertical-align:top;margin-right:5px;margin-top:8px;'>".$customize_name."</span><span style='display:inline-block;vertical-align:top;margin-right:15px;margin-top:8px;'>".JTEXT::_('on')."</span><img src=\"https://ssl.gstatic.com/images/icons/gplus-32.png\" alt='".$alt_icon."' style=\"border:0;width:32px;height:32px;\"/></a>".$explicit;
			break;
		case 'largeicon':
			$html = "<a href='https://plus.google.com/".$page_id."?prsrc=3' style='display:inline-block;text-decoration:none;color:#333;text-align:center;font:13px/16px arial,sans-serif;white-space:nowrap;'><img src='https://ssl.gstatic.com/images/icons/gplus-64.png' alt='".$alt_icon."' style='border:0;width:64px;height:64px;margin-bottom:7px;'><br/><span style='font-weight:bold;'>".$customize_name."</span><br/><span>".JTEXT::_('on')." Google+ </span></a>".$explicit;
			break;
		case 'nobadge':
			$html = $explicit;
			break;
		}
	}else{
		switch ($badge_size) {
	
		case 'badge':
			$html = "<div class='g-plus' data-height='".$height."' data-width='".$width."' data-theme='".$theme."' data-href='https://plus.google.com/".$page_id."' data-size='badge'></div>".$explicit;
			break;
		case 'smallbadge':
			$html = "<div class='g-plus' data-height='".$height."' data-width='".$width."' data-theme='".$theme."' data-href='https://plus.google.com/".$page_id."' data-size='smallbadge'></div>".$explicit;
			break;		
		case 'smallicon':
			$html = "<a href='https://plus.google.com/".$page_id."?prsrc=3' style='cursor:pointer;display:inline-block;text-decoration:none;color:#333;font:13px/16px arial,sans-serif;'><span style='display:inline-block;font-weight:bold;vertical-align:top;margin-right:5px;'>".$customize_name."</span><span style='display:inline-block;vertical-align:top;margin-right:13px;'>".JTEXT::_('on')."</span><img src='https://ssl.gstatic.com/images/icons/gplus-16.png' alt='".$alt_icon."' style=\"border:0;width:16px;height:16px;\"/></a>".$explicit;
			break;
		case 'mediumicon':
			$html = "<a href='https://plus.google.com/".$page_id."?prsrc=3' style='cursor:pointer;display:inline-block;text-decoration:none;color:#333;font:13px/16px arial,sans-serif;'><span style='display:inline-block;font-weight:bold;vertical-align:top;margin-right:5px;margin-top:8px;'>".$customize_name."</span><span style='display:inline-block;vertical-align:top;margin-right:15px;margin-top:8px;'>".JTEXT::_('on')."</span><img src=\"https://ssl.gstatic.com/images/icons/gplus-32.png\" alt=\"".$alt_icon."\" style=\"border:0;width:32px;height:32px;\"/></a>".$explicit;
			break;
		case 'largeicon':
			$html = "<a href='https://plus.google.com/".$page_id."?prsrc=3' style='display:inline-block;text-decoration:none;color:#333;text-align:center;font:13px/16px arial,sans-serif;white-space:nowrap;'><img src='https://ssl.gstatic.com/images/icons/gplus-64.png' alt='".$alt_icon."' style='border:0;width:64px;height:64px;margin-bottom:7px;'><br/><span style='font-weight:bold;'>".$customize_name."</span><br/><span>".JTEXT::_('on')." Google+ </span></a>".$explicit;
			break;
		case 'nobadge':
			$html = $explicit;
			break;
		}
	}
	
	return $html;
	
	
	}
			
}

?>