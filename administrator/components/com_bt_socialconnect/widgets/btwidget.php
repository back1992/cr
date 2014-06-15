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
 class BTWidget extends JObject {
	public $params = null;
	public $name = null;

	
	public function __construct($alias) {
		$lang = JFactory::getLanguage();
		$language_tag = 'en-GB';
		$db = JFactory::getDBO();
		$db->setQuery("SELECT `wgtype`,params from #__bt_widgets WHERE alias ="."'$alias'");
		$result= $db->loadObject();		
		
        $this->params = new JRegistry();
		if($result){
			$this->params->loadString($result->params);
			$this->name = $result->wgtype;			
			require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/'.$this->name.'/'.$this->name.'.php');
			$sstype = $this->name;
			$lang->load($sstype, JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/'.$sstype, $language_tag, true);
		}
    }
	
	public static function display($params){		

	}
	
}

?>