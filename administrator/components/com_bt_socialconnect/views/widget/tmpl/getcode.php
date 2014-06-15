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

<div class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#preview"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_PREVIEW') ;?></label></a> </li>
		<li><a href="#plugin"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_PLUGIN_INSERT') ;?></label></a></li>
		<li><a href="#htmlcode"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_CODE_HTML') ;?></label></a></li>
		
	</ul>
						
<style>
.mvm{
	padding:10px !important ;
}
.code textarea {
   border: 1px solid #BDC7D8;
    width: 600px;
}
</style>
<?php
		$widget = new BTWidget($this->item->alias);
		$name =  $widget->name;	
		$content = $name::display($widget->params);
		$scriptface = "<div id='fb-root'></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = '//connect.facebook.net/en_GB/all.js#xfbml=1';
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>";
		$googlescript = '<script type="text/javascript">
					  (function() {
					   var po = document.createElement(\'script\'); po.type = \'text/javascript\'; po.async = true;
					   po.src = \'https://apis.google.com/js/client:plusone.js\';
					   var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(po, s);
					 })();
					</script>';
		$loginbutton ='<script type="text/javascript">
				function newPopup(pageURL, title,w,h){
					var left = (screen.width/2)-(w/2);
					var top = (screen.height/2)-(h/2);
					var popupWindow = window.open(pageURL,\'connectingPopup\',\'height=\'+h+\',width=\'+w+\',left=\'+left+\',top=\'+top+\',resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes\');
				}
		</script>';
?>	

<div class="tab-content viewdemo">

	<div id="preview" class="tab-pane active">
		<div class="mvm"></div>
		<?php echo $this->loadTemplate('preview');?>
	</div>
	<div id="plugin" class="tab-pane">
		<div class="width-100 fltlft">
			<div class="mvm"></div>
			<div class="code"><textarea  style="width:620px; height:auto;" onclick="this.focus(); this.select()" spellcheck="false" rows="8"><?php echo '{widget:'.$this->item->alias.'}';?></textarea></div>
		</div>
	</div>
	<div id="htmlcode" class="tab-pane">
		<div class="width-100 fltlft">
			
			<?php 			
			$face = strpos($this->item->wgtype, 'facebook'); 
			$google = strpos($this->item->wgtype, 'google'); 
			$login = strpos($this->item->wgtype, 'login_button'); 
			if ($face !== false) {
				?>
					<div class="mvm"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_JAVASCRIPT_INSERT'); ?></label></div>
					<div class="code"><textarea style="width:620px; height:auto" onclick="this.focus(); this.select()" spellcheck="false" rows="8"><?php echo htmlspecialchars($scriptface)?></textarea></div>
				
				<?php 
			}
			if ($google !== false) {
				?>
					<div class="mvm"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_JAVASCRIPT_INSERT'); ?></label></div>
					<div class="code"><textarea style="width:620px; height:auto" onclick="this.focus(); this.select()" spellcheck="false" rows="8"><?php echo htmlspecialchars($googlescript)?></textarea></div>
				<?php 
			}
			if ($login !== false) {
				?>
					<div class="mvm"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_JAVASCRIPT_INSERT'); ?></label></div>
					<div class="code"><textarea style="width:620px; height:auto" onclick="this.focus(); this.select()" spellcheck="false" rows="8"><?php echo htmlspecialchars($loginbutton)?></textarea></div>
				<?php 
			}
			?>
		</div>
		
		<div class="width-100 fltlft">
			<div class="mvm"><label><?php echo Jtext::_('COM_BT_SOCIALCONNECT_HTML_INSERT'); ?></label></div>
			<div class="code">
					<textarea style="width:620px; height:auto" onclick="this.focus(); this.select()" spellcheck="false" rows="8"><?php echo htmlspecialchars($content)?></textarea>
					
			</div>
		
		</div>
	</div>
	
</div>

</div>		



