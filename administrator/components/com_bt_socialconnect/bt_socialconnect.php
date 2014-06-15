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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_bt_socialconnect')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register helper class
JLoader::register('Bt_SocialconnectHelper', dirname(__FILE__) . '/helpers/helper.php');
JLoader::register('Bt_SocialconnectLegacyHelper', dirname(__FILE__) .  '/helpers/legacy.php', true);
Bt_SocialconnectHelper::addAdminScript();
jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('Bt_Socialconnect');
$jinput = JFactory::getApplication()->input;
$controller->execute($jinput->get('task', '', 'CMD'));
$controller->redirect();
