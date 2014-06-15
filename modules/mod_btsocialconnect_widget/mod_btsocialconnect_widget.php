<?php
/**
 * @package 	mod_btsocialconnectwidget - BT Login Module
 * @version		1.0.0
 * @created		February 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Include the syndicate functions only once
require_once (dirname ( __FILE__ ) . '/helper.php');
$wigets =$params->get('widget');

$item = modbt_socialconnect_wigetHelper::getWiget($wigets);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require (JModuleHelper::getLayoutPath ( 'mod_btsocialconnect_widget', 'default'));
?>

