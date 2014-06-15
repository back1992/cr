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
class modbt_socialconnectHelper
{
	public static function loadModule($name,$title){
		$module=JModuleHelper::getModule($name,$title);
		return JModuleHelper::renderModule($module);
	}

	public static function getType()
	{
		$user =  JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
	
	public static function getModules($params) {
		$user =  JFactory::getUser();
		if ($user->get('guest')) return '';
		
		$document = JFactory::getDocument();
		$moduleRender = $document->loadRenderer('module');
		$positionRender = $document->loadRenderer('modules');
		
		$html = '';
		
		$db = JFactory::getDbo();
		$i=0;
		$module_id = $params->get('module_id', array());
		if (count($module_id) > 0) {
			$sql = "SELECT * FROM #__modules WHERE id IN (".implode(',', $module_id).") ORDER BY ordering";
			$db->setQuery($sql);
			$modules = $db->loadObjectList();
			foreach ($modules as $module) {
				
				if ($module->module != 'mod_btsocialconnect_login') {
					$i++;
					$html = $html . $moduleRender->render($module->module, array('title' => $module->title, 'style' => 'xhtml'));
					//$html = $html .$moduleRender->render($module->module, array('title' => $module->title, 'style' => 'rounded'));
					//if($i%2==0) $html.="<br clear='both'>";
				}
			}
		}
	
		$module_position = $params->get('module_position', array());
		if (count($module_position) > 0) {
			foreach ($module_position as $position) {
				$modules = JModuleHelper::getModules($position);
				foreach ($modules as $module) {
					if ($module->module != 'mod_btsocialconnect_login') {
						$i++;
						$html = $html . $moduleRender->render($module, array('style' => 'xhtml'));
						//if($i%2==0) $html.="<br clear='both'>";
					}
				}
			}
		}
		
		if ($html==''){
			$html= $moduleRender->render('mod_menu',array('title'=>'User Menu','style'=>'xhtml'));
		}
		return $html;
	}
	
	public static function fetchHead($params){
		$document	= JFactory::getDocument();
		$header = $document->getHeadData();
		$mainframe = JFactory::getApplication();
		$template = $mainframe->getTemplate();

		$loadJquery = true;
		switch($params->get('loadJquery',"auto")){
			case "0":
				$loadJquery = false;
				break;
			case "1":
				$loadJquery = true;
				break;
			case "auto":
				
				foreach($header['scripts'] as $scriptName => $scriptData)
				{
					if(substr_count($scriptName,'/jquery'))
					{
						$loadJquery = false;
						break;
					}
				}
			break;		
		}
		
		// load js
		if(file_exists(JPATH_BASE.'/templates/'.$template.'/html/mod_btsocialconnect_login/js/default.js'))
		{	
			if($loadJquery)
			{ 
				$document->addScript(JURI::root(true).'/templates/'.$template.'/html/mod_btsocialconnect_login/js/jquery.min.js');
			}
			$document->addScript(JURI::root(true).'/templates/'.$template.'/html/mod_btsocialconnect_login/js/jquery.fancybox.js');
			$document->addScript(JURI::root(true).'/templates/'.$template.'/html/mod_btsocialconnect_login/js/script.js');	
		}
		else{
			if($loadJquery)
			{ 
				$document->addScript(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/js/jquery.min.js');
			}
		
			$document->addScript(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/js/jquery.jscrollpane.min.js');	
			$document->addScript(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/js/script.js');	
			$document->addScript(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/js/jquery.fancybox.js');	
		}
		
		// load css
		if(file_exists(JPATH_BASE.'/templates/'.$template.'/html/mod_btsocialconnect_login/css/style2.0.css'))
		{
			$document->addStyleSheet(  JURI::root(true).'/templates/'.$template.'/html/mod_btsocialconnect_login/css/style2.0.css');
			$document->addStyleSheet(  JURI::root(true).'/templates/'.$template.'/html/mod_btsocialconnect_login/css/jquery.fancybox.css');
		}
		else{
			$document->addStyleSheet(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/css/style2.0.css');
			$document->addStyleSheet(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/css/jquery.fancybox.css');
		}
		
		//bind JText to JS:
		JText::script('REQUIRED_FILL_ALL'); 
		JText::script('E_LOGIN_AUTHENTICATE'); 
		JText::script('REQUIRED_NAME'); 
		JText::script('REQUIRED_USERNAME'); 
		JText::script('REQUIRED_PASSWORD'); 
		JText::script('REQUIRED_VERIFY_PASSWORD'); 
		JText::script('PASSWORD_NOT_MATCH'); 
		JText::script('REQUIRED_EMAIL'); 
		JText::script('EMAIL_INVALID'); 
		JText::script('REQUIRED_VERIFY_EMAIL'); 
		JText::script('EMAIL_NOT_MATCH'); 
	}
		
	
	public static function ajax(){
		$mainframe = JFactory::getApplication('site');
		//JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$isRegister = JRequest::getVar('bttask');
		
		/**
		 * check task is login to do
		 */
		if($isRegister=='login'){
		
		
				if ($return = JRequest::getVar('return', '', 'method', 'base64')) {
					$return = base64_decode($return);
					if (!JURI::isInternal($return)) {
						$return = '';
					}
				}		
				$options = array();
				
				$options['remember'] = JRequest::getBool('remember', false);
				
				$options['return'] = $return;
		
				$credentials = array();
				
				$credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
				
				$credentials['password'] = JRequest::getString('passwd', '', 'post', JREQUEST_ALLOWRAW);
				
				//preform the login action
				$error = $mainframe->login($credentials, $options);
				self::ajaxResponse($error);
				
		}
	}
	public static function loadUserFields()
	{		
		$app = JFactory::getApplication();		
		$db = JFactory::getDBO();					
		$db->setQuery('SELECT * FROM #__bt_user_fields WHERE published = 1  AND show_registration = 1 order by ordering ');
		$r = $db->loadObjectList();		
			foreach ($r as $e)
			{			
				
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
				
            return $r;
        
	}
	
	//get all user field 
	
	static function getFieldname(){
		$db = JFactory::getDbo ();				
		$db->setQuery('SELECT * FROM #__bt_user_fields WHERE published = 1 AND show_listing = 1 order by ordering ');
		$r = $db->loadObjectList();
		return $r;
		
	}
	
	//Get avatar user
	
	static function getAvatar($id){	
		$db		= JFactory::getDbo();
		$db->setQuery('SELECT * FROM #__bt_users WHERE user_id =  '.(int)$id);		
		$results = $db->loadObjectList();
		$avatar ='';
		$alias = self::getFieldname();
		foreach ($results as $i => $item) :	
			$array = (array) $item;	
			foreach ($alias as $a): 
				//Check field type image 
				if (array_key_exists($a->alias, $array)):				
					if($a->type =='image') :				
						if ($array[$a->alias]){
							$avatar =  $array[$a->alias] ;
							break;
						}
						
					endif;
				endif;
			
			endforeach ;
		endforeach ;
			
		return $avatar;
	}
	
	public static function ajaxResponse($message){
		$obLevel = ob_get_level();
		if($obLevel){
			while ($obLevel > 0 ) {
				ob_end_clean();
				$obLevel --;
			}
		}else{
			ob_clean();
		}
		echo $message;
		exit;
	}
}
