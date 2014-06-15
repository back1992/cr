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


class Bt_SocialconnectViewProfile extends JViewLegacy
{
	protected $data;
	protected $form;
	protected $params;
	protected $state;
	protected $active;


	public function display($tpl = null)
	{
		// Get the view data.
		$this->data		= $this->get('Data');
		$this->social= $this->get('Social');		
		$this->newconnect= $this->get('Newconnect');			
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->active =$this->get('Active');
		$this->params	= $this->state->get('params');	

		parent::display($tpl);
	}
	
}
