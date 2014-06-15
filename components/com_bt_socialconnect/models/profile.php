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
$com_path = '/components/com_bt_socialconnect/';
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR. $com_path . '/models', 'Bt_SocialconnectModel');

class Bt_SocialconnectModelProfile extends JModelForm
{
	
	protected $data;

	
	public function checkin($userId = null)
	{		
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

		if ($userId) {
			
			$table = JTable::getInstance('User');

		
			if (!$table->checkin($userId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}
	
	public function checkout($userId = null)
	{		
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');

		if ($userId) {
			
			$table = JTable::getInstance('User');		
			$user = JFactory::getUser();
			
			if (!$table->checkout($user->get('id'), $userId)) {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}
	
	
	
	function getSocialConnect($id,$connect){
		$db		= $this->getDbo();
		$query = 'SELECT *' .
					' FROM #__bt_connections' .
					' WHERE user_id='.$id.' AND social_type ='."'$connect'";
		$db->setQuery( $query );		
		$social= $db->loadObjectList();				
		return ($social);
	
	}
	
	function getSocial(){
		$userId = $this->getState('user.id');	
		$db		= $this->getDbo();
		$query = 'SELECT *' .
					' FROM #__bt_connections' .
					' WHERE user_id='.$userId;
		$db->setQuery( $query );
		$social= $db->loadObjectList();	
		return ($social);
					
	}
	
	/*
	*list social networking  has not connections yet.
	*/
	
	function getNewconnect(){
	
		$userId = $this->getState('user.id');		
		$btnsocial = array();
		
		if(!self::getSocialConnect($userId,'facebook')){
			$btnsocial[]='{login_btn:facebook}';
		}
		if(!self::getSocialConnect($userId,'twitter')){
			$btnsocial[]='{login_btn:twitter}';
		}
		if(!self::getSocialConnect($userId,'google')){
			$btnsocial[]='{login_btn:google}';
		}
		if(!self::getSocialConnect($userId,'linkedin')){
			$btnsocial[]='{login_btn:linkedin}';
		}
		return implode(' ',$btnsocial);
	}
	
	public function getData()
	{
	
		if ($this->data === null) {
			$db		= $this->getDbo();
			$userId = $this->getState('user.id');			
			$this->data	= new JUser($userId);
			
			// Set the base user data.
			$this->data->email1 = $this->data->get('email');
			$this->data->email2 = $this->data->get('email');
		
			
			$temp = (array)JFactory::getApplication()->getUserState('com_bt_socialconnect.edit.profile.data', array());
			foreach ($temp as $k => $v) {
				$this->data->$k = $v;
			}
			
			unset($this->data->password1);
			unset($this->data->password2);
			
			$registry = new JRegistry($this->data->params);
			$this->data->params = $registry->toArray();

			
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('user');

			
			$results = $dispatcher->trigger('onContentPrepareData', array('com_bt_socialconnect.profile', $this->data));

			// Check for errors encountered while preparing the data.
			if (count($results) && in_array(false, $results, true)) {
				$this->setError($dispatcher->getError());
				$this->data = false;
			}
			$loadData = self::getValueData($userId);
			
			if(isset($this->data->user_fields)){
				$this->data->user_fields = Bt_SocialconnectHelper::loadUserFields($this->data->user_fields );
			}else{
				$this->data->user_fields = Bt_SocialconnectHelper::loadUserFields($loadData);
			}
			//Get avatar from userid
			$ImgFormats = array("png", "jpg", "jpeg", "gif", "tiff");			
			
			foreach ($this->data->user_fields AS $key =>$value){
				if($value->type =='image'){
					$PathInfo = pathinfo($value->value);
					 if (@in_array(@strtolower($PathInfo['extension']), $ImgFormats)) {
						$this->data->avatar =$value ->value;
						break;
					 }
					  else{
						$this->data->avatar='';
					 }
				}
			}
			 
		}		
	
		return $this->data;
	}	
	
	
	public static function getValueData($id){	
			
		$db	 = JFactory::getDBO();
		$query ='select a.* FROM #__bt_users AS a where a.user_id ='.$id ;
		$db->setQuery( $query );			
		$row = $db->loadAssoc();	
	 
		return $row;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
	
		$form = $this->loadForm('com_bt_socialconnect.profile', 'profile', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		if (!JComponentHelper::getParams('com_bt_socialconnect')->get('change_login_name'))
		{
			$form->setFieldAttribute('username', 'class', '');
			$form->setFieldAttribute('username', 'filter', '');
			$form->setFieldAttribute('username', 'description','');
			$form->setFieldAttribute('username', 'validate', '');
			$form->setFieldAttribute('username', 'message', '');
			$form->setFieldAttribute('username', 'readonly', 'true');
			$form->setFieldAttribute('username', 'required', 'false');
		}
		
		$app = JFactory::getApplication();
		$params = $app->getParams();	
		if ($params->get('remove_user', 0)) {
			$form->removeField('username');
			$form->setFieldAttribute('email1', 'readonly', 'true');

		}
		return $form;
	}

	
	protected function loadFormData()
	{
		
		return $this->getData();
	}

	protected function preprocessForm(JForm $form, $data, $group = 'user')
	{
		if (JComponentHelper::getParams('com_bt_socialconnect')->get('frontend_userparams'))
		{
			$form->loadFile('frontend', false);
			if (JFactory::getUser()->authorise('core.login.admin')) {
				$form->loadFile('frontend_admin', false);
			}
		}
		parent::preprocessForm($form, $data, $group);
	}

	
	protected function populateState()
	{
		
		$params	= JFactory::getApplication()->getParams('com_bt_socialconnect');

		
		$userId = JFactory::getApplication()->getUserState('com_bt_socialconnect.edit.profile.id');
		$userId = !empty($userId) ? $userId : (int)JFactory::getUser()->get('id');
		
		$this->setState('user.id', $userId);
		
		$this->setState('params', $params);
	}

	public function updateAccesstoken($sociaID,$token){
		if ($sociaID) {		
			$db		= $this->getDbo();
			$query = 'UPDATE  #__bt_connections SET access_token ="'.$token.'"  WHERE social_id ='.$sociaID;
			$db->setQuery( $query );			
			$db->query();				
			return true;
		}	
	}	
	
	public function save($data)
	{	
		
		$userId = (!empty($data['user_id'])) ? $data['user_id'] : (int)$this->getState('user.id');

		$user = new JUser($userId);			
	
		unset($data['sendEmail']);
		
		if (!$user->bind($data)) {
			$this->setError(JText::sprintf('SOCIAL PROFILE BIND FAILED', $user->getError()));
			return false;
		}
		$data['email']		= $data['email1'];		
		$data['password']	= $data['password1'];	
				
		$data['groups'] = null;		
		$modeladmin =JModelLegacy::getInstance('Socialconnect', 'Bt_SocialconnectModel',array('ignore_request' => true));			
		return $modeladmin->save($data);		
		
	}
	
	public function getActive(){
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('user.id');
		if ($userId) {
			$db		= $this->getDbo();
			$query = 'SELECT id' .
					' FROM #__users' .
					' WHERE id='.$userId.' AND block !=0 AND activation!=""';
			$db->setQuery( $query );		
			$rs= $db->loadresult();				
			return ($rs);
		}
	}
}
