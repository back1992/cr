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

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/helper.php';
require_once JPATH_COMPONENT.'/helpers/route.php';
Bt_SocialconnectHelper::addSiteScript();
$controller = JControllerLegacy::getInstance('Bt_Socialconnect');
$jinput = JFactory::getApplication()->input;
$controller->execute($jinput->get('task', 'display', 'CMD'));
$controller->redirect();
