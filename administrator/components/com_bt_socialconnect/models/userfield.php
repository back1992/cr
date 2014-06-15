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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';

class Bt_SocialconnectModelUserfield extends JModelAdmin
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
      if (!empty($record->id))
         return parent::canDelete($record);
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_bt_socialconnect.userfield.'.(int) $record->id);
		}		
		// Default to component settings if neither article nor category known.
		else {
			return parent::canEditState('com_bt_socialconnect');
		}
	}
	
	public function getTable($type = 'userfield', $prefix = 'Bt_SocialconnectTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}	
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_bt_socialconnect.userfield', 'userfield', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}				

		return $form;
	}	
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_bt_socialconnect.edit.userfield.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			
		}		

		return $data;
	}	
	
	protected function preprocessForm(JForm $form, $data, $groups = '')
	{
		$obj = is_array($data) ? JArrayHelper::toObject($data, 'JObject') : $data;		
		if (isset($obj->alias)  && $obj->id > 0) {
			$form->setFieldAttribute('type', 'readonly', 'true');			
		}
		parent::preprocessForm($form, $data);
	}
	
	public  function save($data){

			
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->post->get('jform', array(), 'array');
	
		$dispatcher = JDispatcher::getInstance();		
		$table		= $this->getTable();
		
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('userfield.id');	
		$isNew		= true;		
		
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}
		
		//Coppy image		
		$this->prepareFolders($this->saveDir);			
		$this->prepareFolders($this->images_path);
		
		$path_image_avartar = $this->images_path;		
		if(isset($_FILES["upload_image"]["tmp_name"]) && !empty($_FILES["upload_image"]["tmp_name"])){						
					
				$imgSource = $_FILES["upload_image"]["tmp_name"];												
				$info = getimagesize($imgSource);								
				$imageExt = str_replace('image/', '', $info['mime']);
				$imageName = md5(strtotime($data["name"])).'.' . $imageExt;		
							
				if(JFile::upload($imgSource, $path_image_avartar. $imageName)){									
					$data['default_values'] = $imageName;										
				}								
												
		}
				
		//Check alias if save2copy
		if ($jinput->get('task') == 'save2copy')
			{
				$data['id']=0;		
				
				list($title,$alias) = $this->generateNewTitle(0, $data['alias'], $data['name']);
				$data['name']	= $title;
				$data['alias']	= $alias;	
				
			}		
		
		if (!$table->bind($data))
		{				
			$this->setError($table->getError());
			return false;
		}
		
		$this->prepareTable($table);
		
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}		
		$result = $dispatcher->trigger('onExtensionBeforeSave', array('com_bt_socialconnect.userfield', &$table, $isNew));		
		
		if (in_array(false, $result, true))
		{
			$this->setError($table->getError());
			return false;
		}		
		
		if (!$table->store())
		{		
			$this->setError($table->getError());
			return false;
		}
		
		$dispatcher->trigger('onExtensionAfterSave', array('com_bt_socialconnect.userfield', &$table, $isNew));
		$this->setState('userfield.id',	$table->id);
			
		$this->cleanCache();
		
		$db =  $this->getDbo();
		
			
			switch($table->type){
				case'string':
				case'image':
				case'dropdown':
				$table->type ='VARCHAR(255)';
				break;
			default :
				$table->type=$table->type;
				break;	
			}			
			
			$db->setQuery("SHOW columns FROM #__bt_users where field ='".$table->alias."'");
			$rows = $db->loadResult();			
			if (!$rows){
				$query ="ALTER TABLE #__bt_users ADD `".$table->alias."` ".$table->type." NOT NULL ";					
				$db->setQuery($query);
				$db->query();
			}
			
		return true;
		
	
		
	}
	
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
	
	function getOldata($id){		
		$db = $this->getDbo();		
		$db->setQuery('Select alias from #__bt_user_fields WHERE id > 0 and id = ' .(int)$id . ' and published = 1');
		return $db->loadObjectList();
	}
	
	//Delete custom field and delete column has create in table user
	public function delete(&$pks){
		$db =  $this->getDbo();	
		
		foreach($pks as $pk){
				
				$Oldata = self::getOldata($pk);		
				foreach ($Oldata as $field)
					{
					$db->setQuery("SHOW columns FROM #__bt_users where field ='".$field->alias."'");
					$rows = $db->loadResult();			
					if ($rows){
						$query ="ALTER TABLE #__bt_users DROP  ".$field->alias." ";					
						$db->setQuery($query);
						$db->query();	
					}
				}	
			$query = 'DELETE FROM #__bt_user_fields WHERE id = '. (int) $pk   ;
			$db->setQuery($query);
			$db->query();										
				
		}
		return true;
	}

}

