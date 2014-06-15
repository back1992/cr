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
jimport('joomla.application.component.view');

class Bt_SocialconnectViewWidget extends JViewLegacy
{
	protected $form;	
	protected $item;
	protected $state;
	public function display($tpl = null)
	{		
		$this->form	= $this->get('Form');
		$this->item	= $this->get('Item');		
		$this->state	= $this->get('State');			
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
		$this->setDocument();
	}

	protected function addToolbar()
	{
		$jinput = JFactory::getApplication()->input;		
		$jinput->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);		
		$canDo		= Bt_SocialconnectHelper::getActions($this->state->get('filter.category_id'), $this->item->id);
		JToolBarHelper::title($isNew ? JText::_('COM_BT_SOCIALCONNECT_WIDGET_NEW') : JText::_('COM_BT_SOCIALCONNECT_WIDGET_EDIT'), 'socialconnect-add.png');
		if ($isNew && (count($user->getAuthorisedCategories('com_bt_socialconnect', 'core.create')) > 0)) {
			JToolBarHelper::apply('widget.apply');
			JToolBarHelper::save('widget.save');			
			JToolBarHelper::cancel('widget.cancel');
		}
		else {
			
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId)) {
					JToolBarHelper::apply('widget.apply');
					JToolBarHelper::save('widget.save');			
					
				}	

			
			if ($canDo->get('core.create')) {
				JToolBarHelper::save2copy('widget.save2copy');
			}

			JToolBarHelper::cancel('widget.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();		
	}
	
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_BT_BT_SOCIALCONNECT_WIDGET_CREATING') : JText::_('COM_BT_BT_SOCIALCONNECT_WIDGET_EDITTING'));		
	}
}
