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

class Bt_SocialconnectViewStatistics extends JViewLegacy
{
	protected $numb;
	

	public function display($tpl = null)
	{
		$this->numb		= $this->get('Numberuser');	
		$this->model =$this->getModel('Statistics');	
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		Bt_SocialconnectHelper::addSubmenu('statistics');
		$this->addToolbar();	
		parent::display($tpl);
		$this->setDocument();
		
	}
	protected function addToolbar()
	{		
		JToolBarHelper::title(JText::_('COM_BT_SOCIALCONNECT_STATISTICS_MANAGER_TITLE'), 'statistic.png');		
	}
	 protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_BT_SOCIALCONNECT_STATISTIC_TITLE_MANAGER_TITLE'));
    }
	
	
}
