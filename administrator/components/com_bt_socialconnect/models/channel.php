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
jimport( 'joomla.database.table' );
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';


class Bt_SocialconnectModelChannel extends JModelAdmin
{
	protected $text_prefix = 'COM_BT_SOCIALCONNECT';	
	
	protected function canDelete($record)
	{ 
		  if (!empty($record->id))
			 return parent::canDelete($record);
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_bt_socialconnect.channel.'.(int) $record->id);
		}		
	
		else {
			return parent::canEditState('com_bt_socialconnect');
		}
	}
	
	public function getTable($type = 'channel', $prefix = 'Bt_SocialconnectTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	protected function prepareTable(&$table)
	{	
		$date = JFactory::getDate();
		$user = JFactory::getUser();
			
	}
	
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');
		$jinput = JFactory::getApplication()->input;
		
		if (!($pk = (int) $jinput->get('id', '', 'INT')))
		{
			if ($sstype = $app->getUserState('com_bt_socialconnect.add.channel.sstype'))
			{
				$this->setState('sstype.type', $sstype);
				$this->setState('sstitle.title',  $app->getUserState('com_bt_socialconnect.add.channel.sstitle'));
			
			}
		}
		$this->setState('channel.id', $pk);
		
		$params	= JComponentHelper::getParams('com_bt_socialconnect');
			
		$this->setState('params', $params);
	}
	
	public function getForm($data = array(), $loadData = true)
	{		
	
		if (empty($data))
		{
			$item		= $this->getItem();				
			$sstype		= $item->type;				
		}
		else
		{		
			$sstype		= JArrayHelper::getValue($data, 'channel');			
		}	
		
		$this->setState('item.sstype',$sstype);		
		$form = $this->loadForm('com_bt_socialconnect.channel', 'channel', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form))
		{
			return false;
		}	
		
		if (!$this->canEditState((object) $data))
		{
		
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
		}	
		return $form;
	}

	
	protected function loadFormData()
	{
		$app = JFactory::getApplication();
		
		$data = $this->getItem();		
		$data->author = $data->xml->author;
		$data->version = $data->xml->version;
		$data->description = $data->xml->description;
		$data->created = JFactory::getDate()->toSql();	
		
		
		if (empty($data))
		{
			$data = $this->getItem();
			
			$params = $app->getUserState('com_bt_socialconnect.add.channel.params');
			
			if (is_array($params))
			{
				$data->set('params', $params);
			}
		}	
			
	
		return $data;
	}
	
	/**
	* Get all value in xml file
	*/	
	public function getItem($pk = null)
	{
		
		$pk	= (!empty($pk)) ? (int) $pk : (int) $this->getState('channel.id');
		
		if (!isset($this->_cache[$pk]))
		{
			$false	= false;
			
			$table = $this->getTable();
			
			$return = $table->load($pk);			
			if ($return === false && $error = $table->getError())
			{
				$this->setError($error);
				return $false;
			}			
		
			if (empty($pk))
			{				
				if ($sstype =  $this->getState('sstype.type'))
				{	
					$table->type		= $sstype;				
					$table->title		= $this->getState('sstitle.title');								
													
				}
				else
				{
					$app = JFactory::getApplication();
					$app->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=channels', false));
					return false;
				}
			}	
			
			$properties = $table->getProperties(1);	
		
			$this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');			
			
			$registry = new JRegistry;
			$registry->loadString($table->params);
			
			$this->_cache[$pk]->params = $registry->toArray();				

			$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
			$path	= JPath::clean($client->path.'/administrator/components/com_bt_socialconnect/publishes/'.$table->type.'/'.$table->type.'.xml');

			if (file_exists($path))
			{
				$this->_cache[$pk]->xml = simplexml_load_file($path);				
				
			}
			else
			{
				$this->_cache[$pk]->xml = null;
			}
		}	
	
		return $this->_cache[$pk];
	}
	

	
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{	
		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');		
		
		$sstype		= $this->getState('item.sstype');
		
		$lang		= JFactory::getLanguage();
		
		$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
		$formFile	= JPath::clean($client->path.'/administrator/components/com_bt_socialconnect/publishes/'.$sstype.'/'.$sstype.'.xml');		
		$language_tag = 'en-GB';
		
		
		$lang->load($sstype, $client->path, null, false, false)
		||	$lang->load($sstype, $client->path.'/administrator/components/com_bt_socialconnect/publishes/'.$sstype, $language_tag, true)
		||	$lang->load($sstype, $client->path, $lang->getDefault(), false, false)
		||	$lang->load($sstype, $client->path.'/administrator/components/com_bt_socialconnect/publishes/'.$sstype, $lang->getDefault(), false, false);
		
		if (file_exists($formFile))
		{
			
			if (!$form->loadFile($formFile, false, '//config'))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}
			
			if (!$xml = simplexml_load_file($formFile))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}
			
			$help = $xml->xpath('/extension/help');
			if (!empty($help))
			{
				$helpKey = trim((string) $help[0]['key']);
				$helpURL = trim((string) $help[0]['url']);

				$this->helpKey = $helpKey ? $helpKey : $this->helpKey;
				$this->helpURL = $helpURL ? $helpURL : $this->helpURL;
			}

		}		
		
		parent::preprocessForm($form, $data, $group);
	}	
	
	
	public function save($data = null)
	{		
		
		$jinput = JFactory::getApplication()->input;
		$data = $jinput->post->get('jform', array(), 'array');
		
		//Get value in group when load account 
		$details = $jinput->post->get('group', array(), 'array');		
		
		$data['params']['groupid'] = $details;
				
		$dispatcher = JDispatcher::getInstance();		
		$table		= $this->getTable();
		
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('channel.id');	
		$isNew		= true;		
		
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}
		
		//Check alias if task = save2copy
		
		if ($jinput->get('task') == 'save2copy')
			{
				$data['id']=0;		
				
				list($title,$alias) = $this->generateNewTitle(0, $data['alias'], $data['title']);
				$data['title']	= $title;
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
		$result = $dispatcher->trigger('onExtensionBeforeSave', array('com_bt_socialconnect.channel', &$table, $isNew));		
		
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
		
		$dispatcher->trigger('onExtensionAfterSave', array('com_bt_socialconnect.channel', &$table, $isNew));
		$this->setState('channel.id',	$table->id);
			
		$this->cleanCache();
		
		return true;
	}
	
	function generateNewTitle($sstid,$alias, $title)
	{
		
		$catTable = JTable::getInstance('Channel', 'Bt_SocialconnectTable');
		while ($catTable->load(array('alias'=>$alias))) {
			$m = null;
			if (preg_match('#-(\d+)$#', $alias, $m)) {
				$alias = preg_replace('#-(\d+)$#', '-'.($m[1] + 1).'', $alias);
			} else {
				$alias .= '-2';
			}
			if (preg_match('#\((\d+)\)$#', $title, $m)) {
				$title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $title);
			} else {
				$title .= ' (2)';
			}
		}

		return array($title, $alias);
	}
	/**
	* Check file install
	*/
	public function _isManifest($file)
	{
		// Initialize variables
		$null	= null;
		$mainframe = JFactory::getApplication('administrator');
		$xml	= JFactory::getXML($file);	

		// If we cannot load the xml file return null
		
		if (!$xml) {
			
			unset ($xml);
			return $null;
		}
	/**
	 * Check object, version and type of file xml
	 */
		if( !is_object($xml) ||
			version_compare((string)$xml->attributes()->version, '1.5', '<') ||
			((string)$xml->attributes()->type != 'publish') )
		{
			// Free up xml parser memory and return null
			unset ($xml);
			$mainframe->enqueueMessage(JText::_('COM_BT_SOCAL_INSTALL_NOT_TYPE_SOCIALPUBLISH'),'notice');			
			$mainframe->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=channels', false));
			return null;
		}		
		
		return $xml;
	}
	
	function getPackageFromUpload()
	{
		$jinput = JFactory::getApplication()->input;
		$userfile = $jinput->files->get('install_package');		

		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning(100, JText::_('WARNINSTALLFILE'));
			return false;
		}		

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning(100, JText::_('WARNINSTALLZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning(100, JText::_('No file selected'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 )
		{
			JError::raiseWarning(100, JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build the appropriate paths
		$config 	=JFactory::getConfig();
		
		$tmp_dest 	= $config->get('tmp_path').'/'.$userfile['name'];		
		$tmp_src	= $userfile['tmp_name'];
		
		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);

		return $package;
	}
	
	function install($package){
		
		$mainframe = JFactory::getApplication('administrator');
		if(!$package){
			$mainframe->enqueueMessage(JText::_('COM_BT_SOCIAL_INSTALL_SOCIALPUBLISH_ERROR').' > Code: '.__LINE__,'error');
			return null;
		}
		
		$db = JFactory::getDBO();
		
		$src	= $package['dir'];
		$files 	= JFolder::files( $src, '.xml$' );
	
		#read file xml
		$data = array();
		$xmlfiles = JFolder::files( $src, '.xml$' );
		
		if(!$xmlfiles) return false;
		if(!is_array($xmlfiles)) $xmlfiles = array($xmlfiles);
		$method = '';
		
		for( $i=0; $i<count($xmlfiles); $i++ ) {
			
			$xmlfile = $xmlfiles[$i];
			$xmlpath = JPath::clean($src.'/'.$xmlfile);
			if(!is_file($xmlpath)) 
				continue;
			$xmlobj = $this->_isManifest($xmlpath);			
			
			if(!$xmlobj) continue;
			
			$data = array();
			$data['file'] 		= $xmlfile;			
			$data['type'] 		= substr($xmlfile,0,strlen($xmlfile)-4);
			$data['params'] 	= '';			
			$data['title'] 		= $xmlobj->name ?(string) $xmlobj->name : '';			
			$data['author'] 		= $xmlobj->author ?(string) $xmlobj->author : '';			
			$data['version'] 		= $xmlobj->version ?(string) $xmlobj->version : '';			
			$data['description']= $xmlobj->description ?(string) $xmlobj->description : '';							
			
			break;
		}
		
		
		if(!$data) return false;
		$channel_type_path = JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes';
		if(!JFolder::exists($channel_type_path)) {
			$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_SOCIALPUBLISH_ERROR'),'notice');
			return false;
		}
		
		
		//Create widget
		$newfoldername = substr($data['file'],0,strlen($data['file'])-4);
		$publishpath = $channel_type_path.'/'.$newfoldername;
		$upgrade_channel = false;
		if(JFile::exists($publishpath.'/'.$data['file'])) {
			$mainframe->enqueueMessage("".__LINE__." - ".JText::_('COM_BT_SOCIAL_NEWER_VERSION_ALREADY_INSTALLED'), 'notice');
			return false;
		}
		$oldxml = $publishpath.'/'.$newfoldername.'.xml';

		if(JFolder::exists($publishpath)){
			
			//compare version			
			if(JFile::exists($oldxml)) {
				$oldxml = JPath::clean($oldxml);
				$oldxmlobj 	= $this->_isManifest($oldxml);				
				
				$oldversion = $oldxmlobj->version ?(string) $oldxmlobj->version : '';
				$newversion = $data['version'];
				$versioncp 		= version_compare( $newversion, $oldversion );
				if( $versioncp == -1 ) {
					$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_NEWER_VERSION_ALREADY_INSTALLED'), 'notice');
					return false;
				}
				$upgrade_widget = true;
			}
		} else {
			if(!JFolder::create($publishpath)){
				$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_SOCIALPUBLISH_ERROR').' > Code: '.__LINE__, 'notice');
				return false;
			}
		}

		if(!JFolder::copy( $src.'/', $publishpath.'/','', true )){
			$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_SOCIALPUBLISH_ERROR').' > Code: '.__LINE__,'notice');
			return false;
		}
			
		$data['published'] = 0;			
		
		$table = JTable::getInstance('Channel', 'Bt_SocialconnectTable');		
		$table->check($data);
		if (!$table->bind($data))
		{				
			$this->setError($table->getError());
			return false;
		}
		if (!$table->check())
		{
			$this->setError($table->getError());
			return false;
		}
		
		if (!$table->store()){
			$mainframe->enqueueMessage( "".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_SOCIALPUBLISH_ERROR').' > Code: '.__LINE__, 'notice' );
			return false;
		}
		else{			
			
		
		$msg = 'COM_BT_SOCIAL_INSTALL_SOCIALPUBLISH_SUCCESS';
		if( $upgrade_widget ) {
			$msg = 'COM_BT_SOCIAL_UPGRADE_SOCIALPUBLISH_SUCCESS';
		}
		$mainframe->enqueueMessage("".__LINE__.JText::_($msg));
		$mainframe->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=channels', false));
		}
		return true;
	}
	
	function uninstall() {
	
	 	$mainframe = JFactory::getApplication('administrator');
		$jinput = JFactory::getApplication()->input;
		
		jimport('joomla.installer.installer');
		$installer 	= JInstaller::getInstance();
		$cid = $jinput->post->get('cid', array(), 'array');		

		
		foreach($cid AS $key=> $folder){			
			$dest 	= JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/'.$folder;
			
			if(JFolder::exists($dest)){
				if(JFolder::delete($dest )){
					$db =JFactory::getDBO();
					$query = 'DELETE FROM #__bt_channels WHERE type = \''.$folder."'";
					$db->setQuery($query);
					$db->query();
					return true;
				}
			}else{
				$mainframe->enqueueMessage('Core widget cannot be removed.','notice');
				return false;
			}
		}		
	
	}	

}
