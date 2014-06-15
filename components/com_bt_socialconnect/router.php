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

defined('_JEXEC') or die;


function Bt_socialconnectBuildRoute(&$query){
$segments = array();
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$menu->getItems('component', 'com_bt_socialconnect');
	
	if (isset($query['view'])) {						
		$segments[] = $query['view'];
		 
		 if(isset($query['layout'])&& $query['layout'])  {
			$segments[] = $query['layout'];			
			unset($query['layout']);   	  
			}
		
		unset($query['view']);	
		
	}
	
	return $segments; 
}


function Bt_socialconnectParseRoute($segments)
{
	$vars = array();
	$count = count($segments);
	if (count($segments) < 1) {
		return;
	}
	switch ($count) {
        case 1:
            $vars['view'] = $segments[0];
            break;
        case 2:				
			$layout = $segments[1];
			$vars['view'] = $segments[0];
			$vars['layout'] = $layout;
	}
	return $vars;
	
}