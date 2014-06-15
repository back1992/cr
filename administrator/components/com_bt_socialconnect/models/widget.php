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

class Bt_SocialconnectModelWidget extends JModelAdmin
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
			return $user->authorise('core.edit.state', 'com_bt_socialconnect.widget.'.(int) $record->id);
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
	
	public function getTable($type = 'widget', $prefix = 'Bt_SocialconnectTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	protected function prepareTable(&$table)
	{
	
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);	
		$table->alias		= JApplication::stringURLSafe($table->alias);
		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}		
	}
	
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');
		$jinput = JFactory::getApplication()->input;
		
		if (!($pk = (int) $jinput->get('id', '', 'INT')))
		{
			if ($wgtype = $app->getUserState('com_bt_socialconnect.add.widget.wgtype'))
			{
				$this->setState('wgtype.type', $wgtype);
				$this->setState('wgtitle.title',$app->getUserState('com_bt_socialconnect.add.widget.title'));
			}
		}
		$this->setState('widget.id', $pk);
		
		$params	= JComponentHelper::getParams('com_bt_socialconnect');
			
		$this->setState('params', $params);
	}
	
	public function getForm($data = array(), $loadData = true)
	{		
		// Get the form.
		if (empty($data))
		{
			$item		= $this->getItem();			
			$wgtype		= $item->wgtype;				
		}
		else
		{		
			$wgtype		= JArrayHelper::getValue($data, 'widget');			
		}	
		
		$this->setState('item.wgtype',$wgtype);		
		$form = $this->loadForm('com_bt_socialconnect.widget', 'widget', array('control' => 'jform', 'load_data' => $loadData));
		
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
		
		if (empty($data))
		{
			$data = $this->getItem();
			
			$params = $app->getUserState('com_bt_socialconnect.add.widget.params');
			
			if (is_array($params))
			{
				$data->set('params', $params);
			}
		}	
			
	
		return $data;
	}
	
		
	public function getItem($pk = null)
	{
		
		$pk	= (!empty($pk)) ? (int) $pk : (int) $this->getState('widget.id');
		
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
				if ($wgtype =  $this->getState('wgtype.type'))
				{					
					$table->wgtype		= $wgtype;
					$table->title = $this->getState('wgtitle.title');					
				}
				else
				{
					$app = JFactory::getApplication();
					$app->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=widgets', false));
					return false;
				}
			}	
			
			$properties = $table->getProperties(1);	
		
			$this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');			
			
			$registry = new JRegistry;
			$registry->loadString($table->params);
			
			$this->_cache[$pk]->params = $registry->toArray();				

			$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
			$path	= JPath::clean($client->path.'/administrator/components/com_bt_socialconnect/widgets/'.$table->wgtype.'/'.$table->wgtype.'.xml');

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
		
		$wgtype		= $this->getState('item.wgtype');
		
		$lang		= JFactory::getLanguage();
		
		$client = JApplicationHelper::getClientInfo($this->getState('filter.client_id', 0));
		$formFile	= JPath::clean($client->path.'/administrator/components/com_bt_socialconnect/widgets/'.$wgtype.'/'.$wgtype.'.xml');		
		$language_tag = 'en-GB';
		
		
		$lang->load($wgtype, $client->path, null, false, false)
		||	$lang->load($wgtype, $client->path.'/administrator/components/com_bt_socialconnect/widgets/'.$wgtype, $language_tag, true)
		||	$lang->load($wgtype, $client->path, $lang->getDefault(), false, false)
		||	$lang->load($wgtype, $client->path.'/administrator/components/com_bt_socialconnect/widgets/'.$wgtype, $lang->getDefault(), false, false);
		
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
		
		$dispatcher = JDispatcher::getInstance();		
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int) $this->getState('widget.id');	
		$isNew		= true;
		
		
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}			
		//Check value alias when chose task = save2copy
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
		$result = $dispatcher->trigger('onExtensionBeforeSave', array('com_bt_socialconnect.widget', &$table, $isNew));		
		
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
		
		$dispatcher->trigger('onExtensionAfterSave', array('com_bt_socialconnect.widget', &$table, $isNew));
		$this->setState('widget.id',			$table->id);
			
			$this->cleanCache();
		
		return true;
	}
	
	function generateNewTitle($widgetid,$alias, $title)
	{
		// Alter the title & alias
		$catTable = JTable::getInstance('Widget', 'Bt_SocialconnectTable');
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
		$config 	=& JFactory::getConfig();
		
		$tmp_dest 	= $config->get('tmp_path').'/'.$userfile['name'];		
		$tmp_src	= $userfile['tmp_name'];
		
		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = JInstallerHelper::unpack($tmp_dest);

		return $package;
	}
	
	
	public function _isManifest($file)
	{
		// Initialize variables
		$null	= null;
		$mainframe = &JFactory::getApplication('administrator');
		$xml	= JFactory::getXML($file);	

		// If we cannot load the xml file return null
		
		if (!$xml) {
			
			unset ($xml);
			return $null;
		}
		if( !is_object($xml) ||
			version_compare((string)$xml->attributes()->version, '1.5', '<') ||
			((string)$xml->attributes()->type != 'widget') )
		{
			// Free up xml parser memory and return null
			unset ($xml);
			$mainframe->enqueueMessage(JText::_('COM_BT_SOCAL_INSTALL_NOT_TYPE_WIDGET'),'notice');			
			$mainframe->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=widgets', false));
			return null;
		}
		
		
		return $xml;
	}
	
	function install($package){
		
		$mainframe = JFactory::getApplication('administrator');
		if(!$package){
			$mainframe->enqueueMessage(JText::_('COM_BT_SOCIAL_INSTALL_WIDGET_ERROR').' > Code: '.__LINE__,'error');
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
			$data['wgtype'] 		= substr($xmlfile,0,strlen($xmlfile)-4);
			$data['params'] 	= '';
			$data['note'] = '';				
			
			$data['title'] 		= $xmlobj->name ?(string) $xmlobj->name : '';			
			$data['description']= $xmlobj->description ?(string) $xmlobj->description : '';							
			
			break;
		}
		
		
		if(!$data) return false;
		$widget_type_path = JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets';
		if(!JFolder::exists($widget_type_path)) {
			$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_WIDGET_ERROR'),'notice');
			return false;
		}
		
		
		//Create widget
		$newfoldername = substr($data['file'],0,strlen($data['file'])-4);
		$widgetpath = $widget_type_path.'/'.$newfoldername;
		$upgrade_widget = false;
		if(JFile::exists($widgetpath.'/'.$data['file'])) {
			$mainframe->enqueueMessage("".__LINE__." - ".JText::_('COM_BT_SOCIAL_NEWER_VERSION_ALREADY_INSTALLED'), 'notice');
			return false;
		}
		$oldxml = $widgetpath.'/'.$newfoldername.'.xml';

		if(JFolder::exists($widgetpath)){
			
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
			if(!JFolder::create($widgetpath)){
				$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_WIDGET_ERROR').' > Code: '.__LINE__, 'notice');
				return false;
			}
		}

		if(!JFolder::copy( $src.'/', $widgetpath.'/','', true )){
			$mainframe->enqueueMessage("".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_WIDGET_ERROR').' > Code: '.__LINE__,'notice');
			return false;
		}
			
		$data['published'] = 0;			
		
		$table = JTable::getInstance('Widget', 'Bt_SocialconnectTable');		
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
			$mainframe->enqueueMessage( "".__LINE__.JText::_('COM_BT_SOCIAL_INSTALL_WIDGET_ERROR').' > Code: '.__LINE__, 'notice' );
			return false;
		}
		else{			
			
		
		$msg = 'COM_BT_SOCIAL_INSTALL_WIDGET_SUCCESS';
		if( $upgrade_widget ) {
			$msg = 'COM_BT_SOCIAL_UPGRADE_WIDGET_SUCCESS';
		}
		$mainframe->enqueueMessage("".__LINE__.JText::_($msg));
		$mainframe->redirect(JRoute::_('index.php?option=com_bt_socialconnect&view=widgets', false));
		}
		return true;
	}
	
	function uninstall() {
	
	 	$mainframe = JFactory::getApplication('administrator');
		$jinput = JFactory::getApplication()->input;
		
		jimport('joomla.installer.installer');
		$installer 	= JInstaller::getInstance();
		$cid = $jinput->post->get('cid', array(), 'array');		

		$table = JTable::getInstance('Widget', 'Bt_SocialconnectTable');
		foreach($cid AS $key=> $folder){			
			$dest 	= JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/'.$folder;
			
			if(JFolder::exists($dest)){
				if(JFolder::delete($dest )){
					$db =JFactory::getDBO();
					$query = 'DELETE FROM #__bt_widgets WHERE wgtype = \''.$folder."'";
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
