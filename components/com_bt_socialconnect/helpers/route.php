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

class Bt_SocialconnectHelperRoute
{
	
	public static function &getItems()
	{
		static $items;

		// Get the menu items for this component.
		if (!isset($items)) {
			// Include the site app in case we are loading this from the admin.
			require_once JPATH_SITE.'/includes/application.php';

			$app	= JFactory::getApplication();
			$menu	= $app->getMenu();
			$com	= JComponentHelper::getComponent('com_users');
			$items	= $menu->getItems('component_id', $com->id);

			// If no items found, set to empty array.
			if (!$items) {
				$items = array();
			}
		}

		return $items;
	}

	
	public static function getLoginRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'login') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}

	
	public static function getProfileRoute()
	{
		
		$items	= self::getItems();
		$itemid	= null;

	
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'profile') {
				$itemid = $item->id;
				break;
			}

		}
		return $itemid;
	}

	
	public static function getRegistrationRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'registration') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}


	public static function getRemindRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'remind') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}

	
	public static function getResendRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'resend') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}


	public static function getResetRoute()
	{
		// Get the items.
		$items	= self::getItems();
		$itemid	= null;

		// Search for a suitable menu id.
		foreach ($items as $item) {
			if (isset($item->query['view']) && $item->query['view'] === 'reset') {
				$itemid = $item->id;
				break;
			}
		}

		return $itemid;
	}
}
