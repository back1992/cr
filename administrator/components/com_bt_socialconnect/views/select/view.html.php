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

class Bt_SocialconnectViewSelect extends JViewLegacy
{

	protected $folder;
	
	function display($tpl = null)
	{	
		$Items	= $this->get('Items');
		
		$folder	= $this->get('Folder');
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}		
		$this->assignRef('folder',		$folder);
		$this->assignRef('Items',		$Items);
		parent::display($tpl);
	}
}
