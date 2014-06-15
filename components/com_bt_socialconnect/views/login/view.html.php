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


class Bt_SocialconnectViewLogin extends JViewLegacy
{
	protected $form;
	protected $params;
	protected $state;
	protected $user;


	public function display($tpl = null)
	{
		// Get the view data.
		$this->user		= JFactory::getUser();
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');
		$this->model =$this->getModel('login');	
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
	
		$active = JFactory::getApplication()->getMenu()->getActive();
		if (isset($active->query['layout'])) {
			$this->setLayout($active->query['layout']);
		}
		

		parent::display($tpl);
	}	
	
}
