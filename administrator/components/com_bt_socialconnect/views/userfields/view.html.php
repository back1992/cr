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

class Bt_SocialconnectViewUserfields extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');	
		$this->legacy = Bt_SocialconnectLegacyHelper::isLegacy();

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}		
		Bt_SocialconnectHelper::addSubmenu('userfields');
		$this->addToolbar();		
		parent::display($tpl);
		
		$this->setDocument();
	}
	
	protected function addToolbar()
	{
		$canDo	= Bt_SocialconnectHelper::getActions($this->state->get('filter.category_id'));
		$user		= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_BT_SOCIALCONNECT_USER_FIELD_TITLE'), 'userfield.png');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_bt_socialconnect', 'core.create'))) > 0 ) {
			JToolBarHelper::addNew('userfield.add');
		}
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::publish('userfields.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('userfields.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::divider();			
		}
		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolBarHelper::editList('userfield.edit');
		}		

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'userfields.delete');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_bt_socialconnect');
			JToolBarHelper::divider();
		}
		
	}
	  protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_BT_SOCIALCONNECT_USERS_FIELDS_MANAGER_TITLE'));
    }
	
	 protected function getSortFields() {
        return array(
            'u.id' => JText::_('JGRID_HEADING_ID'),
            'u.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'u.published' => JText::_('JSTATUS'),
        );
    }
}
