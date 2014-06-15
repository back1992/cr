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

jimport('joomla.application.component.modeladmin');
jimport('joomla.access.access');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

//require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class Bt_SocialconnectModelSocialconnect extends JModelAdmin
{
	protected $text_prefix = 'COM_BT_SOCIALCONNECT';	
	protected $images_path;
	/**
	 * Class constructor.
	 *
	 * @param	array	$config	A named array of configuration variables.
	 *
	 * @return	JControllerForm
	 * @since	1.6
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->saveDir =JPATH_SITE .'/images/bt_socialconnect/';
		$this->images_path = $this->saveDir .'avatar/';	
		
	}	
	protected function canDelete($record)
	   {
		  if (!empty($record->user_id))
			 return parent::canDelete($record);
	   }
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing .
		if (!empty($record->user_id)) {
			return $user->authorise('core.edit.state', 'com_bt_socialconnect.socialconnect.'.(int) $record->user_id);
		}		
		
		else {
			return parent::canEditState('com_bt_socialconnect');
		}
	}
	
	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	 */
	 
	public function getTable($type = 'Socialconnect', $prefix = 'Bt_SocialconnectTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_bt_socialconnect.socialconnect', 'socialconnect', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}	
		
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		if($params->get('remove_user')){
			$form->removeField('username');
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_bt_socialconnect.edit.socialconnect.data', array());	
		if (empty($data)) {
			$data = $this->getItem();				
		}		
		
		return $data;
	}
	/*
	* Get all user id
	*/
	function GetUserids(){
		$db = $this->getDbo();
		$db->setQuery('Select user_id  from #__bt_users ');
		return $db->loadObjectList();
	}
	
	function GetSocialconnet($id){
		$db = JFactory::getDbo();
		$db->setQuery('Select *  from #__bt_connections WHERE user_id ='.(int)$id);
		return $db->loadObjectList();
	}
	
	/*
	* Get all data with user id
	*/
	
	function getOldata($id){		
		$db = $this->getDbo();		
		$db->setQuery('Select * from #__bt_users WHERE user_id > 0 and user_id = ' .(int)$id . '');
		return $db->loadObjectList();
	}
	
	function findIdOld($id, $array)
	{
		foreach ($array as $key => $item)
		{
			if ($item->user_id == $id)
			{
				return $key;
			}
		}
		return -1;
	}
	
	
	public function save($data)
	{			

		$pk			= (!empty($data['user_id'])) ? $data['user_id'] : 0;
		$user		= JUser::getInstance($pk);	
		$my = JFactory::getUser();
		$db = $this->getDbo();
		$params = JComponentHelper::getParams('com_bt_socialconnect');	
		
		//load plugin assigment
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('btsocialconnect');	
	
		if($params->get('remove_user')){
		
			$data['username'] = $data['email'];
		}
		//Check groups of user when save
		
		if(!empty($data['groups'])){		
			$iAmSuperAdmin = $my->authorise('core.admin');
			if ($iAmSuperAdmin && $my->get('id') == $pk)
			{			
				$stillSuperAdmin = false;
				$myNewGroups = $data['groups'];
				foreach ($myNewGroups as $group)
				{
					$stillSuperAdmin = ($stillSuperAdmin) ? ($stillSuperAdmin) : JAccess::checkGroup($group, 'core.admin');
				}
				if (!$stillSuperAdmin)
				{
					$this->setError(JText::_('COM_BT_SOCIALCONNECT_ERROR_CANNOT_DEMOTE_SELF'));
					return false;
				}
			}
		}		
	
		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError($user->getError());
			return false;
		}
		
		//Get value user field 
		$user_fields = $data['user_fields'];	

	if ($user->save())
		{				
			$this->setState('socialconnect.id', $user->id);			
			
				$this->prepareFolders($this->saveDir);			
				$this->prepareFolders($this->images_path);	
				$path_image_avartar = $this->images_path;
				$data['user_id']= $user->id;	
				$data['params'] = $user->params;				
				$IdUserOld = self::GetUserids();
				
				$IdOld = self::findIdOld($data['user_id'], $IdUserOld);	
				$Oldata = self::getOldata($user->id);
			
				//Upload image 
				
				if(isset($_FILES["user_fields"]["tmp_name"]) && !empty($_FILES["user_fields"]["tmp_name"])){						
					
						$source = $_FILES["user_fields"]["tmp_name"];
						foreach($source AS $key =>$imgSource){
							if(!empty($imgSource)){
							$info = getimagesize($imgSource);	
							$imageExt = str_replace('image/', '', $info['mime']);
							$imageName = md5($data['user_id'] . strtotime("now")).'_('.$data['username'].'_'.$key.')_'. '.' . $imageExt;						
							
							if(!empty($imgSource)){
								if(JFile::copy($imgSource, $path_image_avartar. $imageName)){	
									foreach ($Oldata as $img)
									{			
										@unlink(JPATH_SITE.'/images/bt_socialconnect/avatar/'.$img->$key);
									}
									$user_fields[$key] = $imageName;
											
								}
								else{						
									return false;
								
								}
							}
							}
						}
				}			
								
			
				//Conver string to save
					$keys = array_keys($user_fields);
					$values = array_values($user_fields);
					$value= array();
					foreach($values as $key=> $field){							
						if(is_array($field)) $field = implode(',',$field);
						if(empty($field)) $field = '';								
						$value[] ='\''.$field.'\'';
					}
				// End 
				//Check user is new or no
				if ($IdOld >= 0)
				{				
						$data['updated_time'] = JFactory::getDate()->toSql();	
						$update ='';
						foreach($keys as $i => $key){
							$update .= $key . '=' . $value[$i] . ($i < count($keys) -1  ? ',' : '');
						}
						
					//Update Bt_users				
						$query='UPDATE  #__bt_users SET '.$update.' WHERE  user_id =' .(int)($data['user_id']);						
						$db->setQuery($query);
						$result =$db->query();					
						
					//Update Bt_connection	
					if(isset($data['enabled_publishing'])&& is_array($data['enabled_publishing'])){
						foreach ($data['enabled_publishing'] AS $key=>$value){
							$query='UPDATE  #__bt_connections  SET  enabled_publishing =\'' . $value . '\',updated_time =\''.$data['updated_time'].'\' WHERE user_id =\'' .($data['user_id']).'\' AND social_type =\'' .$key.'\'';
							$db->setQuery($query);
							$db->query();
						}
						
					}
				}		
				else{										
						if(!empty($keys)){
							$key =','.implode(',',$keys);
						}else{
							$key ='';
						}
						if(!empty($value)){
							$value =',' . (implode(',',$value));
						}else{
							$value ='';
						}
					
						//INSERT INTO  #__bt_users(user_id,profile_link,birthday,avatar) values(1,'http://','')	
						$query='INSERT INTO  #__bt_users(user_id'.$key.') VALUES(\''.$data['user_id'].'\''.$value .')';						
						$db->setQuery($query);
						$db->query();			
						
						
						// Check user is social connect network or register nomal
						if(isset($data['socialId']) && $data['socialId'] !=''){
							$data['created_time'] = JFactory::getDate()->toSql();
							$data['updated_time'] = '';								
							$query='INSERT INTO  #__bt_connections VALUES(\''.''.'\',\'' . ($data['user_id']) . '\',\'' . $data['socialId'] . '\', \''.($data['loginType']).'\',\''.($data['access_token']).'\',\''.($data['params']).'\',\''.($data['enabled_publishing']).'\', \''.($data['created_time']).'\', \''.($data['updated_time']).'\')';
							$db->setQuery($query);
							$db->query();	
							//Auto login if user is social network connect
							self::loginSocial($data['email']);
						}
						else{
							//Check config auto login when save 
							if($params->get('userautologin',1)=='1'){
								$app = JFactory::getApplication();	
								if(!$app->isAdmin()){
									self::loginSocial($data['email']);
								}
							}
						}
				}				
				$results = $dispatcher->trigger('onBtSocialconnectSave', array($data, ''));				
			
			return true;	
			
	 }
	else{
			$this->setError($user->getError());
			return false;
		}
	}
	
	//Delete user profile
	
	public function delete(&$pks){
		
		$user	= JFactory::getUser();		
		$iAmSuperAdmin	= $user->authorise('core.admin');
		$allow = $user->authorise('core.delete', 'com_users');
		// Don't allow non-super-admin to delete a super admin
		$allow = (!$iAmSuperAdmin && JAccess::check($pk, 'core.admin')) ? false : $allow;
	
		//Get value of custom field
		$Obalias =self::getValuealias();			
			foreach($pks as $pk){
				//Get old data
				$Oldata = self::getOldata($pk);				
				foreach ($Oldata as $img)
				{		
					foreach($Obalias AS $key=>$alias){
						$value = $alias->alias;
						//Check field custom is type image 
						$ImgFormats = array("png", "jpg", "jpeg", "gif", "tiff");//Etc. . . 
						$PathInfo = pathinfo($img->$value);
						  if (@in_array(@strtolower($PathInfo['extension']), $ImgFormats)) {
							@unlink(JPATH_SITE.'/images/bt_socialconnect/avatar/'.$img->$value);
						}
					}
				}
				//Delete account in table bt_users
				$query = 'DELETE FROM #__bt_users WHERE user_id = '. (int) $pk   ;
				$this->_db->setQuery( $query ); 
				if ($this->_db->query()) {		
					//Delete connections of users					
					$query = 'DELETE FROM #__bt_connections WHERE user_id = ' .(int)$pk;
					$this->_db->setQuery( $query );
					if (!$this->_db->query()) {
							$this->setError( $this->_db->getErrorMsg() );
							return false;
					} 					
					//Delete account table users				
					if ($allow)
					{
						if (in_array($user->id, $pks))
						{
							$this->setError(JText::_('COM_BT_SOCIALCONNECT_ERROR_CANNOT_DELETE_SELF'));
							return false;
						}
						else{						
							$query = 'DELETE FROM #__users WHERE id = ' . $pk;
							$this->_db->setQuery( $query );
							 if (!$this->_db->query()) {
									$this->setError( $this->_db->getErrorMsg() );
									return false;
							}
						}
					}
					else
					{
						// Prune items that you can't change.
						unset($pks[$i]);
						JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
					}
										
										
				}
				 else {
					$this->setError( $this->_db->getErrorMsg() );
					return false;
				}
				
			}
		
	}	
	//Get all alias of custom field
	public static function getValuealias(){	
			
		$db	 = JFactory::getDBO();
		$query ='SELECT a.alias FROM #__bt_user_fields AS a WHERE a.published = 1 ' ;
		$db->setQuery( $query );			
		$row = $db->loadObjectlist();	
	 
		return $row;
	}	
	//Create folder to save image bt_socialconnect
	function prepareFolders($path)
	{
		self::createFolder($path);		
	}
	
	function createFolder($path){
		if (!is_dir($path))
		{
			JFolder::create($path, 0755);
			$html = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write($path.'index.html', $html);
		}
	}
	
	function getContentUrl($url) {
	
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	  curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	  curl_setopt($ch, CURLOPT_AUTOREFERER, false);
	  curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
	  curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	  curl_setopt($ch, CURLOPT_HEADER, 0);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	  $file = curl_exec($ch);
	  if($file === false) trigger_error(curl_error($ch));

	  curl_close ($ch);
	  return $file;
	}
	//Assigned groups
	public function getAssignedGroups($userId = null)
	{
		
		$userId = (!empty($userId)) ? $userId : (int)$this->getState('socialconnect.id');
		
		if (empty($userId))
		{
			$result = array();
			$config = JComponentHelper::getParams('com_users');		
			
			if ($groupId = $config->get('new_usertype'))
			{
				$result[] = $groupId;
			}
		}
		else
		{
			$result = JUserHelper::getUserGroups($userId);
		}

		return $result;
	}
	
	function activate(&$pks)
	{
		
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();		
		$iAmSuperAdmin	= $user->authorise('core.admin');
		$table		= $this->getTable();
		$pks		= (array) $pks;

		JPluginHelper::importPlugin('user');

		// Access checks.
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk))
			{
				$old	= $table->getProperties();
				$allow	= $user->authorise('core.edit.state', 'com_users');
				
				$allow = (!$iAmSuperAdmin && JAccess::check($pk, 'core.admin')) ? false : $allow;

				if (empty($table->activation))
				{
					
					unset($pks[$i]);
				}
				elseif ($allow)
				{
					$table->block		= 0;
					$table->activation	= '';
					
					try
					{
						if (!$table->check())
						{
							$this->setError($table->getError());
							return false;
						}
						// Trigger the onUserBeforeSave event.
						$result = $dispatcher->trigger('onUserBeforeSave', array($old, false, $table->getProperties()));
						if (in_array(false, $result, true))
						{
							// Plugin will have to raise it's own error or throw an exception.
							return false;
						}
						
						if (!$table->store())
						{
							$this->setError($table->getError());
							return false;
						}
						
						$dispatcher->trigger('onUserAfterSave', array($table->getProperties(), false, true, null));
					}
					catch (Exception $e)
					{
						$this->setError($e->getMessage());

						return false;
					}
				}
				else
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}
			}
		}

		return true;
	}	
	//Set login user
	public static function loginSocial($email) {
	
		$db = JFactory::getDbo ();
		$app = JFactory::getApplication ();
		
		$sql = "SELECT * FROM #__users WHERE email = " . $db->quote ( $email );
		$db->setQuery ( $sql );
		$result = $db->loadObject ();
		
		$jUser = JFactory::getUser ( $result->id );
		$instance = $jUser;
		$instance->set ( 'guest', 0 );
		// Register the needed session variables
		$session = JFactory::getSession();
		$session->set ( 'user', $jUser );
		// Check to see the the session already exists.                        
		$app->checkSession ();
		
		$db->setQuery ( 'UPDATE ' 
			. $db->quoteName ( '#__session' ) 
			. ' SET ' . $db->quoteName ( 'guest' ) 
			. ' = ' 
			. $db->quote ( $instance->get ( 'guest' ) ) 
			. ',' . '   ' 
			. $db->quoteName ( 'username' ) 
			. ' = ' . $db->quote ( $instance->get ( 'username' ) ) 
			. ',' . '   ' 
			. $db->quoteName ( 'userid' ) 
			. ' = ' 
			. ( int ) $instance->get ( 'id' ) 
			. ' WHERE ' . $db->quoteName ( 'session_id' ) . ' = ' . $db->quote ( $session->getId () ) );
		$db->query (); 
		
		// Hit the user last visit field
		$instance->setLastVisit ();
	}
	
	function block(&$pks, $value = 1)
	{
		// Initialise variables.
		$app		= JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		$user		= JFactory::getUser();
		// Check if I am a Super Admin
		$iAmSuperAdmin	= $user->authorise('core.admin');
		$table		= $this->getTable();
		$pks		= (array) $pks;

		JPluginHelper::importPlugin('user');
		
		foreach ($pks as $i => $pk)
		{
			if ($value == 1 && $pk == $user->get('id'))
			{
				// Cannot block yourself.
				unset($pks[$i]);
				JError::raiseWarning(403, JText::_('COM_BT_SOCIALCONNECT_USERS_ERROR_CANNOT_BLOCK_SELF'));

			}
			elseif ($table->load($pk))
			{
				$old	= $table->getProperties();
				$allow	= $user->authorise('core.edit.state', 'com_users');				
				$allow = (!$iAmSuperAdmin && JAccess::check($pk, 'core.admin')) ? false : $allow;
			
				$options = array(
					'clientid' => array(0, 1)
				);
				if ($allow)
				{
					
					if ($table->block == $value)
					{
						unset($pks[$i]);
						continue;
					}

					$table->block = (int) $value;
				
					if ($table->block === 0)
					{
						$table->resetCount = 0;
					}					
					try
					{
						if (!$table->check())
						{
							$this->setError($table->getError());
							return false;
						}
						
						$result = $dispatcher->trigger('onUserBeforeSave', array($old, false, $table->getProperties()));
						if (in_array(false, $result, true))
						{							
							return false;
						}
						
						if (!$table->store())
						{
							$this->setError($table->getError());
							return false;
						}

						
						$dispatcher->trigger('onUserAfterSave', array($table->getProperties(), false, true, null));
					}
					catch (Exception $e)
					{
						$this->setError($e->getMessage());

						return false;
					}
					
					if ($value)
					{
						$app->logout($table->id, $options);
					}
				}
				else
				{					
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}
			}
		}

		return true;
	}
	

}
