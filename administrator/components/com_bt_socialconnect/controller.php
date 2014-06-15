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


class Bt_SocialconnectController extends JControllerLegacy
{
	
	protected $default_view = 'cpanel';

	 /**
     * display task
     *
     * @return void
     */
	 
	public function display($cachable = false, $urlparams = false)
	{
		$jinput = JFactory::getApplication()->input;
		
		// set default view if not set
		
		//Bt_SocialconnectHelper::addSubmenu($jinput->get('view', 'cpanel', 'CMD'));		
		$view		= $jinput->get('view', 'socialconnects', 'CMD');
		$layout 	= $jinput->get('layout', 'socialconnects', 'CMD');
		$id			= (int)$jinput->get('id', '', 'int');
	
		if ($view == 'socialconnect' && $layout == 'edit' && !$this->checkEditId('com_bt_socialconnect.edit.socialconnect', $id)) {
			
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnects', false));

			return false;
		}
		elseif ($view == 'connection' && $layout == 'edit' && !$this->checkEditId('com_bt_socialconnect.edit.connection', $id)) {
			
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=connections', false));

			return false;
		}
		

		parent::display();

		return $this;
	}
	
}
