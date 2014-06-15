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

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

class Bt_SocialconnectModelLogin extends JModelForm
{

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_bt_socialconnect.login', 'login', array('load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{		
		$app	= JFactory::getApplication();
		$data	= $app->getUserState('users.login.form.data', array());
		$jinput = JFactory::getApplication()->input;
		 
		// check for return URL from the request first
		if ($return = $jinput->get('return', '', 'base64')) {
			$data['return'] = base64_decode($return);
			if (!JURI::isInternal($data['return'])) {
				$data['return'] = '';
			}
		}

		
		if (!isset($data['return']) || empty($data['return'])) {
			$data['return'] = 'index.php?option=com_bt_socialconnect&view=profile';
		}
		$app->setUserState('users.login.form.data', $data);

		return $data;
	}

	
	protected function populateState()
	{
		// Get the application object.
		$params	= JFactory::getApplication()->getParams('com_bt_socialconnect');

		// Load the parameters.
		$this->setState('params', $params);
	}

	
	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		
		JPluginHelper::importPlugin($group);
		
		$dispatcher	= JDispatcher::getInstance();
		
		$results = $dispatcher->trigger('onContentPrepareForm', array($form, $data));
		
		if (count($results) && in_array(false, $results, true)) {
			// Get the last error.
			$error = $dispatcher->getError();

			// Convert to a JException if necessary.
			if (!($error instanceof Exception)) {
				throw new Exception($error);
			}
		}
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


}
