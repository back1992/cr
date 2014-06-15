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

class Bt_SocialconnectViewSocialconnects extends JViewLegacy
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
		$this->model =	$this->getModel('Socialconnects');			

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}		
		Bt_SocialconnectHelper::addSubmenu('socialconnects');
		$this->addToolbar();		
		parent::display($tpl);
		
		$this->setDocument();
	}

	protected function addToolbar()
	{
		$canDo	= Bt_SocialconnectHelper::getActions($this->state->get('filter.category_id'));
		$user		= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_BT_SOCIALCONNECT_USER_MANAGER_TITLE'), 'usermanager.png');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_bt_socialconnect', 'core.create'))) > 0 ) {
			JToolBarHelper::addNew('socialconnect.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolBarHelper::editList('socialconnect.edit');
		}		

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'socialconnects.delete');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_bt_socialconnect');
			JToolBarHelper::divider();
		}
		
	}
	  protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_BT_SOCIALCONNECT_USERS_MANAGER_TITLE'));
    }
}
