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
jimport('joomla.application.component.controlleradmin');

class Bt_SocialconnectControllerConnections extends JControllerAdmin
{
	
	public function __construct($config = array())
	{	
		parent::__construct($config);		
	}
	
	 /**
     * Proxy for getModel.
     * @since	1.6
     */
	public function getModel($name = 'Connection', $prefix = 'Bt_SocialconnectModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
