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

class Facebook_likebox extends BTWidget {	
	
	public static function display($params){		
		$href='';
		$height = 0;
		switch($params->get('type','likebox')){
		case'likebox':
			$href = 'http://www.facebook.com/plugins/likebox.php?href=';
			$href .= urlencode(trim($params->get('href')));
			$href .='&connections='.$params->get('connections',10);
			if($params->get('show_stream'))
			{
				$href .= "&amp;stream=".$params->get('show_stream');
			}
			if($params->get('border_color'))
			{
				$href .= "&amp;border_color=".urlencode($params->get('border_color'));
			}
			if($params->get('show_header'))
			{
				$href .= "&amp;header=".$params->get('show_header');
			}
			if($params->get('force_wall'))
			{
				$href .= "&amp;force_wall=".$params->get('force_wall');
			}
			$height = $params->get('height');
			if($height)
			{
				$href .= "&amp;height=".$height;
			}
			break;
		default:
			$href = 'http://www.facebook.com/plugins/follow.php?href=';
			$href .= urlencode(trim($params->get('profile')));
			if($params->get('layout'))
			{
				$href .= "&amp;layout=".$params->get('layout');
			}
			if($params->get('font'))
			{
				$href .= "&amp;font=".$params->get('font');
			}
			$height = $params->get('height_follow');
			break;
		}
		
		if($params->get('show_faces'))
		{
			$href .= "&amp;show_faces=".$params->get('show_faces');
		}
		if($params->get('width'))
		{
			$href .= "&amp;width=".$params->get('width');
		}
		if($params->get('colorscheme'))
		{
			$href .= "&amp;colorscheme=".$params->get('colorscheme');
		}
		if($params->get('width')){
			$width = $params->get('width').'px';
		}else{
			$width="100%";
		}
		if($height){
			$height = $height.'px';
		}else{
			$height="100%";
		}
		
		$htmlCode ='<div class="bt-facebookpage">';
		$htmlCode .='<iframe src="'.$href.'" scrolling="no" ';
		$htmlCode .='frameborder="0" ';
		$htmlCode .='style="border:none; overflow:hidden; width:'.$width.';height:'.$height.'" '; 	
		$htmlCode .='allowTransparency="true"> ';
		$htmlCode .='</iframe> ';
		$htmlCode .='</div>';
		
		return $htmlCode;
	}
			
}

?>