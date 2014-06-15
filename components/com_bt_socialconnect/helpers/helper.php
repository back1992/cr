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

/**
 * BTSocialconnect Html Helper
 *
 * @package		Joomla.Site
 * @subpackage	com_users
 * @since		1.6
 */
 
abstract class Bt_SocialconnectHelper {

	public static function addSiteScript() {
	
		$document = JFactory::getDocument();
		$header = $document->getHeadData();
		$params = JComponentHelper::getParams("com_bt_socialconnect");
		JHTML::_('behavior.framework');
		$loadJquery = true;
		foreach ($header['scripts'] as $scriptName => $scriptData) {
			if (substr_count($scriptName, '/jquery')) {
				$loadJquery = false;
				break;
			}
		}
		if ($loadJquery) {
			$document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/jquery.min.js');
		}
		$document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/menu.js');
		$document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/default.js');
		$document->addStyleSheet(JURI::root()  . 'components/com_bt_socialconnect/assets/icon/admin.css');      
		$document->addStyleSheet(JURI::root()  . 'components/com_bt_socialconnect/assets/css/legacy.css');      
		$document->addStyleSheet(JURI::root()  . 'components/com_bt_socialconnect/assets/css/menu.css');      
		 		
		
	}
	
	/*
	* Check value to load form (register and profile)
	*/
	public static function loadUserFields($els)
	{
		$app = JFactory::getApplication();		
		
		if (empty($els))
		{
			$els = array();
		}
		$db = JFactory::getDBO();					
		$db->setQuery('SELECT * FROM #__bt_user_fields WHERE published = 1  AND show_registration = 1 order by ordering ');
		$r = $db->loadObjectList();		
			foreach ($r as $e)
			{			
				if (array_key_exists($e->alias, $els))
				{
				
					$e->value = $els[$e->alias];
					if($e->type=='dropdown'){
					if(is_array($e->default_values)) $e->default_values = implode(',',$e->default_values);
						$e->default_values =@unserialize($e->default_values);						
					}
					
				}else{
					switch($e->type){										
						case 'dropdown':							
							if(is_array($e->default_values)) $e->default_values = implode(',',$e->default_values);
							$e->default_values =@unserialize($e->default_values);
							$e->value = '';
							break;
						default:
							$e->value = $e->default_values;
							break;
					}
				}
			}
				
            return $r;
        
	}
	
}
