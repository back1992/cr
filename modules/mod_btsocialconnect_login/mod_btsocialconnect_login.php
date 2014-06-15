<?php
/**
 * @package 	mod_btsocialconnect - BT Login Module
 * @version		1.1.0
 * @created		February 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'cms.captcha.captcha' );
jimport ( 'joomla.application.component.view' );
jimport ( 'joomla.application.component.helper' );
jimport ( 'joomla.plugin.plugin' );


// Include the syndicate functions only once
require_once (dirname ( __FILE__ ) . '/helper.php');
modbt_socialconnectHelper::fetchHead ( $params );

// load language 
$language = JFactory::getLanguage();
$language_tag = $language->getTag(); // loads the current language-tag
JFactory::getLanguage()->load('plg_captcha_recaptcha',JPATH_ADMINISTRATOR,$language_tag,true);
JFactory::getLanguage()->load('mod_btsocialconnect_login',JPATH_SITE,$language_tag,true);
JFactory::getLanguage()->load('lib_joomla',JPATH_SITE,$language_tag,true);
JFactory::getLanguage()->load('com_users',JPATH_SITE,$language_tag,true);
JFactory::getLanguage()->load('com_bt_socialconnect',JPATH_SITE,$language_tag,true);

$mainframe = JFactory::getApplication ();

if(JRequest::getVar("bttask")=="login" ||JRequest::getVar("bttask")=="register"){

	modbt_socialconnectHelper::ajax();
}

$mainframe = JFactory::getApplication ();


//get position display
$align = $params->get ( 'align_option' );

//get color setting
$textColor=$params->get('text_button_color','#fff');

$showLogout= $params->get('logout_button',1);
//setting component to integrated

$moduleRender = '';
$linkOption = '';
$linkOption = 'index.php?option=com_bt_socialconnect&view=registration';


//get option tag active modal
$loginTag = $params->get ( 'tag_login_modal' );
if($params->get('enabled_registration_tab')==1){
	$registerTag = $params->get ( 'tag_register_modal' );
}else{
	$registerTag='';
}

$type = modbt_socialconnectHelper::getType ();

$return = $params ->get($type);

if($return =='' && $type == "login"){
	$return ='index.php?option=com_bt_socialconnect&view=profile';
}
if($return =='' && $type== 'logout'){
	$return ='index.php?option=com_bt_socialconnect&view=login';
}
$return_decode = $return;

$return_decode = str_replace('&amp;','&',JRoute::_($return_decode));
$loggedInHtml = modbt_socialconnectHelper::getModules ( $params );

$user =  JFactory::getUser ();

//setting display type
if ($params->get ( "display_type" ) == 1) {
	$effect = 'btl-dropdown';
} else {
	$effect = 'btl-modal';
}

//setting for registration 
$usersConfig = JComponentHelper::getParams ( 'com_users' );
$enabledRegistration = false;
$viewName = JRequest::getVar ( 'view', 'registry' );
$enabledRecaptcha = 'none';
if ($usersConfig->get ( 'allowUserRegistration' ) && $params->get ( "enabled_registration_tab", 1 ) == 1 && ($viewName != "registration") ) {
	$enabledRegistration = true;
	$enabledRecaptcha = JFactory::getConfig ()->get ( 'captcha' );	
	if ($enabledRecaptcha == 'recaptcha' && $user->id ==0) {
		// 	create instance captcha, get recaptcha
		$recaptchaPlg = JPluginHelper::getPlugin ( 'captcha', 'recaptcha' );
		$recaptchaPlgParams = new JRegistry ( $recaptchaPlg->params );
		$publicKey = $recaptchaPlgParams->get ( 'public_key' );
		$captcha = JCaptcha::getInstance ( 'recaptcha' );
		$reCaptcha = $captcha->display ( $publicKey, '' );
	}
}
$language = JFactory::getLanguage ();
$avatar = modbt_socialconnectHelper::getAvatar($user->id);
$user_fields = modbt_socialconnectHelper::loadUserFields();
require (JModuleHelper::getLayoutPath ( 'mod_btsocialconnect_login', $params->get('layout', 'default') ));
?>

