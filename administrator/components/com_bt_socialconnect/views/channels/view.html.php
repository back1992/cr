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

class Bt_SocialconnectViewChannels extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $folder;
	protected $legacy;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');	
		$this->folder = $this->get('ItemFolder');			
		$this->legacy = Bt_SocialconnectLegacyHelper::isLegacy();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}		
		Bt_SocialconnectHelper::addSubmenu('channels');
		$this->addToolbar();		
		parent::display($tpl);
		
		$this->setDocument();
	}
	
	protected function addToolbar()
	{
		$canDo	= Bt_SocialconnectHelper::getActions($this->state->get('filter.category_id'));
		$user		= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_BT_SOCIALCONNECT_SYSTEM_CONNECTION_MANAGER_TITLE'), 'systemconnect.png');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_bt_socialconnect', 'core.create'))) > 0 ) {
			if ($canDo->get('core.create')) {			
			$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Popup', 'new', 'JTOOLBAR_NEW', 'index.php?option=com_bt_socialconnect&view=social&amp;tmpl=component', 500, 250);
		}
		}
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::publish('channels.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('channels.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::divider();			
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolBarHelper::editList('channel.edit');
		}

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'channels.delete');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_bt_socialconnect');
			JToolBarHelper::divider();
		}
		
	}	
	
	protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_BT_SOCIALCONNECT_SYSTEM_CONNECTIONS_MANAGER_TITLE'));
    }
	
	protected function getSortFields() {
        return array(
            's.id' => JText::_('JGRID_HEADING_ID'),
            's.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            's.published' => JText::_('JSTATUS'),
        );
    }
}
