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
 
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/btwidget.php');

class Google_interactive_posts extends BTWidget {	
	
	public static function display($params){	
	$spriteimage = JURI::root().'components/com_bt_socialconnect/images/btn_icons_sprite.png';
	?>
	<style type="text/css">
	  #myBtn.demo {
		padding: 5px;
		background: #fff;
		cursor: pointer;
		line-height: 20px;
		border: 1px solid #e6e6e6;
		border-radius: 4px;
	  }
	  #myBtn.demo .icon {
		width: 20px;
		height: 20px;
		display: inline-block;
		background:url(<?php echo $spriteimage;?>) transparent 0 -40px no-repeat;
	  }
	  #myBtn.demo:hover {
		background-color: #cc3732;
		color: #fff;
		border: #dd4b39;
	  }
	  #myBtn.demo:hover .icon {
		background:url(<?php echo $spriteimage;?>) transparent 0 0px no-repeat;
	  }
	</style>

	<?php
	$client_id = $params->get('client_id',306040699264);	
	$conten_url = $params->get('conten_url','');
	$cookiepolicy = $params->get('cookiepolicy','');
	$gg_label = $params->get('gg_label','');
	$calltoactionurl = $params->get('calltoactionurl','');
	$calltoactiondeeplinkid = $params->get('calltoactiondeeplinkid','');
	$prefilltext = $params->get('prefilltext','Come learn about interactive posts with me!');
	$button_text = $params->get('button_text','Invite your friends!');
	$contentdeeplinkid = $params->get('contentdeeplinkid','');
	
	$html ='<span id="myBtn" class="demo g-interactivepost"			
			data-contenturl="'.$conten_url.'"
			data-contentdeeplinkid="'.$contentdeeplinkid .'"
			data-clientid="'.$client_id.'.apps.googleusercontent.com" 
			data-cookiepolicy="'.$cookiepolicy.'" 
			data-prefilltext="'.$prefilltext.'" 
			data-calltoactionlabel="'.$gg_label.'"
			data-calltoactionurl="'.$calltoactionurl.'"  
			data-calltoactiondeeplinkid="'.$calltoactiondeeplinkid.'">
			<span class="icon">&nbsp;</span>
			<span class="label">'.$button_text.'</span></span>';
	
	return $html;
	
	}
	
			
}

?>