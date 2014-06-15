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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class Bt_SocialconnectViewCpanel extends JViewLegacy {
   
    function display($tpl = null) {
		Bt_SocialconnectLegacyHelper::getCSS();
		$this->legacy = Bt_SocialconnectLegacyHelper::isLegacy(); 
		Bt_SocialconnectHelper::addSubmenu('cpanel');
		$this->addToolBar();
		/*---------------*/
        $buttons = $this->getButtons();		
		$channel =$this->getSocials();
		$extension =$this->getExtension();
		$widget =$this->getWidget();
		$user= $this->getUser();
		$number_click =$this->getClick();
		$content = $this->getContent();
		//Assign 
        $this->assign('buttons', $buttons);        
        $this->assign('channel', $channel);        
        $this->assign('extension', $extension);        
        $this->assign('widget', $widget);        
        $this->assign('user', $user);        
        $this->assign('number_click', $number_click);        
        $this->assign('content', $content);        
        parent::display($tpl);       
        $this->setDocument();
    }
	
	protected function addToolBar() {
       JToolBarHelper::preferences('com_bt_socialconnect');
		JToolBarHelper::divider();
		$this->sidebar = '';		
    }    
    
    protected function setDocument() {
        $document = JFactory::getDocument();
        JToolBarHelper::title(JText::_('COM_BT_SOCIALCONNECT_SUBMENU_CPANEL'), 'socialconnect.png');
        $document->setTitle(JText::_('COM_BT_SOCIALCONNECT_CPANEL_TITLE'));
    }

    function getButtons() {

        return array(

            array(
                'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnects'),
                'image' => JURI::root() . 'components/com_bt_socialconnect/assets/icon/user-manager.png',
                'text' => JText::_('COM_BT_SOCIALCONNECT_USER_MANAGER_CPANEL'),
                'class' => '',
                'rel' => ''
            ),
			array(
                'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=userfields'),
                'image' => JURI::root() . 'components/com_bt_socialconnect/assets/icon/user-fields.png',
                'text' => JText::_('COM_BT_SOCIALCONNECT_USER_FIELDS_MANAGER'),
                'class' => '',
                'rel' => ''
            ), 
			array(
                'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnect&layout=edit'),
                'image' => JURI::root() . 'components/com_bt_socialconnect/assets/icon/user-group-new.png',
                'text' => JText::_('COM_BT_SOCIALCONNECT_ADD_NEW_USER'),
                'class' => '',
                'rel' => ''
            ),
            array(
                'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=userfield&layout=edit'),
                'image' => JURI::root() . 'components/com_bt_socialconnect/assets/icon/add-field.png',
                'text' => JText::_('COM_BT_SOCIALCONNECT_ADD_NEW_USER_FIELD'),
                'class' => '',
                'rel' => ''
            )
			
        );
    }
	//get Social publish
	
	function getSocials(){
		$db = JFactory::getDBO();
			$sql = 'SELECT `j`.`id`, j.`title`, `j`.`alias`, `j`.`type` FROM `#__bt_channels` AS `j`'.
					' WHERE j.`published` = 1
					ORDER BY j.`ordering` ASC';
			$db->setQuery( $sql );
			$exAddons = $db->loadObjectList();			
			$newArray = array();
			
				foreach($exAddons As $key =>$addon){
			
					switch($addon->type){
						case'facebookgroup':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/fb-group.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						case'facebookprofile':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/fb-profiel.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						case'facebookpage':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/fb-page.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						case'linkedincompanies':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/in-company.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;					
						case'linkedingroup':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/in-group.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						case'linkedinprofile':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/in-profile.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						case'twitterprofile':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/tt-profile.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						case'sendmail':
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/message.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
							break;
						default:
							$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=channel&task=channel.edit&id='.$addon->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/social.png',
										'text' => JText::_($addon->title),
										'class' => '',
										'rel' => ''
									);
									break;
					}
				
				}
				
				$newArray[] =array(
						'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=social&tmpl=component'),
						'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/add_social.png',
						'text' => JText::_('COM_BT_SOCIALCONNECT_ADD_NEW_SOCIALSUBMIT'),
						'class' => 'modal',
						'rel' => '{handler: \'iframe\', size: {x: 500, y: 250}, onClose: function() {}}'
						);			
			
			return $newArray;
	
	}
	
	function getExtension(){
		$db = JFactory::getDBO();
		$query = 'SELECT `e`.`extension_id`,`e`.`name`, e.`element` FROM `#__extensions` AS `e`'.
					' WHERE e.`enabled` = 1'.
					' AND e.`type`="plugin"'.
					' AND e.`folder` ="btsocialconnect"';
			
			$db->setQuery( $query );
			$extension = $db->loadObjectList();			
			$newArray = array();
			
			foreach($extension As $key =>$ex){		
				switch($ex->element){
					case'bt_profile_k2':
						$newArray[] =array(
									'link' => JRoute::_('index.php?option=com_plugins&view=plugin&task=plugin.edit&extension_id='.$ex->extension_id),
									'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/k2.png',
									'text' => JText::_($ex->name),
									'class' => '',
									'rel' => ''
								);
						break;
					case'bt_profile_joomsocial':
						$newArray[] =array(
									'link' => JRoute::_('index.php?option=com_plugins&view=plugin&task=plugin.edit&extension_id='.$ex->extension_id),
									'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/icon_jomsocial.png',
									'text' => JText::_($ex->name),
									'class' => '',
									'rel' => ''
								);
						break;
					case'bt_profile_virtuemart':
						$newArray[] =array(
									'link' => JRoute::_('index.php?option=com_plugins&view=plugin&task=plugin.edit&extension_id='.$ex->extension_id),
									'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/virtuemart.png',
									'text' => JText::_($ex->name),
									'class' => '',
									'rel' => ''
								);
						break;
					case'bt_profile_kunenaforum':
						$newArray[] =array(
									'link' => JRoute::_('index.php?option=com_plugins&view=plugin&task=plugin.edit&extension_id='.$ex->extension_id),
									'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/th-kunena.png',
									'text' => JText::_($ex->name),
									'class' => '',
									'rel' => ''
								);
						break;
					case'bt_profile_cb':
						$newArray[] =array(
									'link' => JRoute::_('index.php?option=com_plugins&view=plugin&task=plugin.edit&extension_id='.$ex->extension_id),
									'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/community_builder.png',
									'text' => JText::_($ex->name),
									'class' => '',
									'rel' => ''
								);
						break;
					default:
						$newArray[] =array(
									'link' => JRoute::_('index.php?option=com_plugins&view=plugin&task=plugin.edit&extension_id='.$ex->extension_id),
									'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/exdf.png',
									'text' => JText::_($ex->name),
									'class' => '',
									'rel' => ''
								);
						break;
				}
			}
			
			return $newArray;
	
	}
	
	function getWidget(){
		$db = JFactory::getDBO();
		$query = 'SELECT `w`.`id`, w.`title`,w.`wgtype` FROM `#__bt_widgets` AS `w`'.
					' WHERE w.`published` = 1'.					
					' ORDER BY w.`ordering` ASC';
			
		$db->setQuery( $query );
		$widgets = $db->loadObjectList();			
		$newArray = array();
		
		foreach($widgets AS $key =>$widget){		
		
			 switch($widget->wgtype){
				case'facebook_commend':
					$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=widget&task=widget.edit&id='.$widget->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/w-fbcomemnt.png',
										'text' => JText::_($widget->title),
										'class' => '',
										'rel' => ''
									);
					break;
				case'google_comment':
					$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=widget&task=widget.edit&id='.$widget->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/w-google.png',
										'text' => JText::_($widget->title),
										'class' => '',
										'rel' => ''
									);
					break;
				case'linkedin_job':
					$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=widget&task=widget.edit&id='.$widget->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/w-linkedinjob.png',
										'text' => JText::_($widget->title),
										'class' => '',
										'rel' => ''
									);
					break;
				case'twitter_feed':
					$newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=widget&task=widget.edit&id='.$widget->id),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/w-twitterfedd.png',
										'text' => JText::_($widget->title),
										'class' => '',
										'rel' => ''
									);
					break;
							
			 
			 }
		}
			
			 $newArray[] =array(
										'link' => JRoute::_('index.php?option=com_bt_socialconnect&view=widgets'),
										'image' => JURI::root() . 'administrator/components/com_bt_socialconnect/assets/image/w-more.png',
										'text' => JText::_('More'),
										'class' => '',
										'rel' => ''
								);			
									
			return $newArray;	 
	
	}
	
	function  getUser(){	
		$db = JFactory::getDBO();
		$query = 'SELECT COUNT(DISTINCT  `u`.`user_id`) FROM `#__bt_connections` AS `u`';					
		$db->setQuery( $query );
		$count = $db->loadResult();
		return $count; 		
	}
	
	function  getContent(){	
		$db = JFactory::getDBO();
		$query = 'SELECT  `m`.`type`,`m`.`title`,`m`.`url`,`m`.`trigger`,`m`.`published` FROM `#__bt_messages` AS `m` ORDER  BY `m`.`id` DESC LIMIT 5';					
		$db->setQuery( $query );
		$result = $db->loadObjectList();	
		return $result; 		
	}
	
	function getClick(){
		$db = JFactory::getDBO();
		$query = 'SELECT  SUM(m.event) from #__bt_messages AS m';					
		$db->setQuery( $query );
		$result = $db->loadResult();	
		return $result; 
	}

}
