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
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.modal');
$i = 0;
$s = 0;
$e = 0;
$w = 0;
$stt = 0;
?>
<?php if(!$this->legacy): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span12">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>	
<div class="adminform">
	<div class="cpanel-left span6">
		<div class="cpanel_section"><span class="bt-space"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_USERMANAGER_CPANEL'); ?></span><span class="bt-toggle toogle-user"><span class="bt-down"></span></span></div>
		<div class="user_setting">
			<div class="cpanel">
			<?php foreach ($this->buttons as $button){
				$i ++;
			?>
				<div class="icon-wrapper">
					<div class="icon">
						<a class="<?php echo $button['class']?>" rel="<?php echo $button['rel']?>" href="<?php echo $button['link']; ?>">
							<img src="<?php echo $button['image']?>" />
							<span><?php echo $button['text']; ?></span></a>
					</div>
				</div>
			<?php
				if($i%6 == 0) echo '<br clear="all">';
			} ?>
			</div>
		</div>
		<div class="cpanel_section"><span class="bt-space"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_SOCIALSUBMIT_CPANEL');?> </span><span class="bt-toggle toogle-social"><span class="bt-down"></span></span></div>
		<div class="social_setting">
			<div class="cpanel">
			<?php foreach ($this->channel as $publish){
				$s ++;
			?>
				<div class="icon-wrapper">
					<div class="icon">
						<a class="<?php echo $publish['class']?>" rel="<?php echo $publish['rel']?>" href="<?php echo $publish['link']; ?>">
							<img src="<?php echo $publish['image']?>" />
							<span><?php echo $publish['text']; ?></span></a>
					</div>
				</div>
			<?php
				if($s%6 == 0) echo '<br clear="all">';
			} ?>
			</div>
		</div>
		<div class="cpanel_section"><span class="bt-space"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_EXTENSIONS_CPANEL'); ?> </span><span class="bt-toggle toogle-extension"><span class="bt-down"></span></span></div>
		<div class="extension_setting">
			<div class="cpanel">
			<?php foreach ($this->extension as $exten){
				$e ++;
			?>
				<div class="icon-wrapper">
					<div class="icon">
						<a class="<?php echo $exten['class']?>" rel="<?php echo $exten['rel']?>" href="<?php echo $exten['link']; ?>" target="_blank">
							<img src="<?php echo $exten['image']?>" />
							<span><?php echo $exten['text']; ?></span></a>
					</div>
				</div>
			<?php
				if($e%6 == 0) echo '<br clear="all">';
			} ?>
			</div>
		</div>
		<div class="cpanel_section"><span class="bt-space"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_WIDGET_CPANEL') ;?> </span><span class="bt-toggle toogle-widget"><span class="bt-down"></span></span></div>
		<div class="widget_setting">
			<div class="cpanel">
			<?php foreach ($this->widget as $wid){
				$w ++;
			?>
				<div class="icon-wrapper">
					<div class="icon">
						<a class="<?php echo $wid['class']?>" rel="<?php echo $wid['rel']?>" href="<?php echo $wid['link']; ?>">
							<img src="<?php echo $wid['image']?>" />
							<span><?php echo $wid['text']; ?></span></a>
					</div>
				</div>
			<?php
				if($w%6 == 0) echo '<br clear="all">';
			} ?>
			</div>
		</div>
		<div class="cpanel_about"><span class="bt-space"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_BTSOCIALVERSION_CPANEL'); ?> </span></div>
	</div>
	<div class="cpanel-right span6">
	<div class="bt-static">
		
		<div class="bt-staspace">
			<div class="bt-title"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_STATISTIC_CPANEL'); ?></div>	
			<div class="bt-user">
				<div class="bt-userstatic">
					<span class="icon"></span>
					<span class="views">
						<span class="detail"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_USERS_CPANEL'); ?></span>
						<span class="detail nub"><?php echo number_format($this->user); ?></span>
					</span>
					<a href="index.php?option=com_bt_socialconnect&view=connections"><div class="gga"> <span class="more"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_VIEW_MORE_CPANEL') ?></span><span class="down"></span></div></a>
				</div>
				<?php $params = JComponentHelper::getParams('com_bt_socialconnect'); ?>
				<?php if($params->get('count_post',1) ==1):?>
				<div class="bt-click">
					<span class="icon"></span>
					<span class="views">
						<span class="detail"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_CLICKS_CPANEL');?></span>
						<span class="detail nub"><?php echo number_format($this->number_click); ?></span>
					</span>
					<a href="index.php?option=com_bt_socialconnect&view=messages"><div class="gga"> <span class="more"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_VIEW_MORE_CPANEL') ?></span><span class="down"></span></div></a>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<a href="index.php?option=com_bt_socialconnect&view=statistics"><div class="bt-viewmore"><div class="showmore"> <span class="more"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_VIEWALL_MORE_CPANEL') ?></span><span class="down"></span></div></div></a>
	</div>
	<div class="bt-staspace">
			<div class="bt-title"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_LATEST_SUBMIT_CPANEL') ?></div>	
		<table cellspacing="0" border="0" class="bt-table">
		<tr>
			<th><?php echo Jtext::_('COM_BT_SOCIALCONNECT_TT_CPANEL')?></th>
			<th><?php echo Jtext::_('COM_BT_SOCIALCONNECT_CONTENNAME_CPANEL')?></th>
			<th><?php echo Jtext::_('COM_BT_SOCIALCONNECT_CONTENTYPE_CPANEL')?></th>
			<th><?php echo Jtext::_('COM_BT_SOCIALCONNECT_SOCIALTYPE_CPANEL')?></th>
			<th><?php echo Jtext::_('COM_BT_SOCIALCONNECT_STATUS_CPANEL')?></th>
		</tr>
		<?php if(!empty($this->content)):?>
		<?php foreach ($this->content AS $key =>$content):?>
		<?php $stt++; ?>
			<tr>
				<td><?php echo $stt;?></td>
				<td><a href="<?php echo $content->url;?>" target="_blank"><?php echo $content->title ;?></a></td>
				<td><?php echo $content->trigger;?></td>
				<td><?php echo $content->type;?></td>
				<td>
				<?php
					switch($content->published){
						case 1:
							echo'<span class="bt-submited"><span class="white-text">'.Jtext::_('COM_BT_SOCIALCONNECT_SUBMITED_CPANEL').'</span></span>';
							break;
						case 2:
							echo'<span class="bt-pending white-text">'.Jtext::_('COM_BT_SOCIALCONNECT_PENDING_CPANEL').'</span>';
							break;
						default:
							echo'<span class="bt-error white-text"><span class="text">'.Jtext::_('COM_BT_SOCIALCONNECT_ERROR_CPANEL').'</span></span>';
							break;
					}						
				?>
				</td>
			</tr>
		<?php endforeach;?>
		<?php endif; ?>
		</table>
	</div>
	<a href="index.php?option=com_bt_socialconnect&view=messages"><div class="bt-viewmore"><div class="showmore"> <span class="more"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_VIEWALL_CPANEL') ?></span><span class="down"></span></div></div></a>
	<div class="bt-staspace">
		<div class="bt-title"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_ABOUT_CPANEL') ?></div>
		<div class="bt-description">
			<fieldset class="adminform">
				<h3>BT Social Connect component for Joomla 2.5 & 3.x</h3>
				<a href="http://bowthemes.com" target="_blank"><img src="<?php echo JURI::root().'components/com_bt_socialconnect/assets/icon/socialconnect.png';?>"></a>
				<p align="justify">
					BT Social Connect is a multi-task social networking tool for Joomla. This component helps turn your site into a social hub. Main features are social auto submission (including Facebook and Twitter), social log in and registration, and add-on social widgets. With friendly CPanel being equipped, you find easy to manage article statistics and message logs. Effortless social marketing gives effective outcome with BT Social Connect.
				</p>
				<br clear="both" />
				<h3>Components features:</h3>
				<ul class="list-style">			
					<li>Connect with social networks (Facebook, Twitter, Google, LinkeIn) to register account and share Joomla articles.</li>
					<li>Easy to customize user profile  with flexible data fields; integrate between data fields and social accounts.</li>
					<li>Support for registration and login display module.</li>
					<li>Setup and insert widgets to articles or display them as modules.</li>
					<li>Setup and manage channels of social networks to share articles.</li>
					<li>Easy to manage shared posts and count visits of articles from multiple social networks.</li>
					<li>Statistics of used elements of widgets, user connections and channels.</li>
					<li>Compatible with Joomla 1.7, 2.5 and 3.x.</li>
					<li>Cross Browser Support: IE7+, Firefox 2+, Safari 3+, Chrome 8+, Opera 9+.</li>
				</ul>
				<br />
				<br />
				<p>
					<b>Your current versions is 1.1.0-beta. <a target="_blank" href="http://bowthemes.com">Find our latest versions now</a></b>
				</p>
				<p>
					<b><a target="_blank" href="http://bowthemes.com/showcase/joomla-templates.html">Bow Themes Joomla templates</a> |
					<a target="_blank" href="http://bowthemes.com/showcase/joomla-extensions.html">Bow Themes Joomla extension</a></b>
				</p>

			</fieldset>
		</div>
			<div class="gga"> <a href="http://bowthemes.com/" target="_blank"><span class="check"><?php echo Jtext::_('COM_BT_SOCIALCONNECT_CHECKLATES_VERSION_CPANEL') ?></span></a></div>
	</div>
	
	</div>
</div>
</div>