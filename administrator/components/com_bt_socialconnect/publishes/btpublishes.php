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
jimport( 'joomla.registry.registry' );
 class BTPublishes extends JObject {
	public $params = null;
	public $attachment = null;
	public $name = null;	
	public $type = null;	
	
	public function __construct($alias,$db) {
		$lang = JFactory::getLanguage();
		$language_tag = 'en-GB';	
		$db->setQuery("SELECT `type`,params from #__bt_channels WHERE alias ='$alias' AND published =1");
		$result= $db->loadObject();		
		
        $this->params = new JRegistry();
		if($result){
			$this->params->loadString($result->params);
			$this->type = $result->type;			
			require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/'.$this->type.'/'.$this->type.'.php');			
			$sstype=$this->type;
			$lang->load($sstype, JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/'.$sstype, $language_tag, true);
		}
    }
	
	public static function postMessage($params,$attachment){		

	}
	
}

?>