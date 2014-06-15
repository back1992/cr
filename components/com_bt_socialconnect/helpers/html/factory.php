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

class BTJFactory extends JFactory{

	public static function createDbo(){
		$conf = self::getConfig();

		$host = $conf->get('host');
		$user = $conf->get('user');
		$password = $conf->get('password');
		$database = $conf->get('db');
		$prefix = $conf->get('dbprefix');
		$driver = $conf->get('dbtype');
		$debug = $conf->get('debug');

		$options = array('driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'database' => $database, 'prefix' => $prefix,'reconnect'=>true);
		
		$db = JDatabase::getInstance($options);

		if ($db instanceof Exception)
		{
			if (!headers_sent())
			{
				header('HTTP/1.1 500 Internal Server Error');
			}
			jexit('Database Error: ' . (string) $db);
		}

		if ($db->getErrorNum() > 0)
		{
			die(sprintf('Database connection error (%d): %s', $db->getErrorNum(), $db->getErrorMsg()));
		}

		$db->setDebug($debug);

		return $db;
	}
}

?>