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

class Bt_SocialconnectLegacyHelper{
    
    
    public static function isLegacy(){
        if (version_compare(JVERSION, '3.0', 'ge')) {
            return false;
        }else{
            return true;
        } 
    }
    public static function getController(){
        if (version_compare(JVERSION, '3.0', 'ge')) {
            
            return JControllerLegacy::getInstance('Bt_Socialconnect');
            
        }else{
            return JController::getInstance('Bt_Socialconnect');
        } 
    }
	
	public static function getCSS(){
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $document = JFactory::getDocument();
			$jinput = JFactory::getApplication()->input;
			$view = $jinput->get('view', 'cpanel', 'CMD');            
            $document->addStyleSheet(JUri::base(). 'components/com_bt_socialconnect/views/' . $view . '/tmpl/css/legacy.css');
            return JControllerLegacy::getInstance('Bt_Socialconnect');
        }
    }	
    
}