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
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/btwidget.php');
?>

<?php

		$params = JComponentHelper::getParams('com_bt_socialconnect');		
		$appID = $params->get('fbappId','');
		if($appID!=''){
			$appID	=	'&appId='.$appID.'';
		}				
		$widget = new BTWidget($this->item->alias);
		$name =  $widget->name;	
		$content  = "<div id='fb-root'></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = '//connect.facebook.net/en_GB/all.js#xfbml=1".$appID."';
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>";
		$content .= '<script type="text/javascript">
					  (function() {
					   var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
					   po.src = \'https://apis.google.com/js/client:plusone.js\';
					   var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
					 })();
					</script>';
		$content .= $name::display($widget->params);
		
?>	
<div class="tab-content">

		<div class="code">
		
				<?php echo $content;?>
				
		</div>
		
</div>

