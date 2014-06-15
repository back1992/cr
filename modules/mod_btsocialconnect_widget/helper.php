<?php
/**
 * @package 	mod_btsocialconnect - BT Login Module
 * @version		2.5.1
 * @created		April 2012

 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.user.helper' );
class modbt_socialconnect_wigetHelper
{
	public static function getWiget($widgets){	
		$newArray= array();	
		$html ='';
		
		if(count($widgets) ==1 && $widgets[0] == '0'){		
			$options =self::getOptions();
			foreach($options AS $key =>$value){
				$newArray[] = $value->value;
			}
		}
		else{			
			$newArray = $widgets;
		}
		
		$html ='{widget:'.implode(',',$newArray).'}';
		return $html;
		
	}
	public static function getOptions(){
		$db		= JFactory::getDbo();
		$db->setQuery("SELECT  c.alias AS value FROM #__bt_widgets AS c WHERE published = 1  ORDER BY c.title, c.ordering ASC");
		$results = $db->loadObjectList();
			
		return $results;
	
	}
	
}
