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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>
<style>
	.form-validate{
		border:1px solid #F2F3F4 !important;
	}
	.menu {
		background:#F2F3F4 !important;
	}
	.tab-content{	
		
		border-left:1px solid  #e5e4e2  ;
		border-right:1px solid  #e5e4e2  ;
		
	}
	#filter-bar{
		background:  ButtonHighlight   ;
	}
	.menu li.active a {
		background: none repeat scroll 0 0 ButtonHighlight   !important;		
		padding:8px;
	}
</style>

<script type="text/javascript">
	function installAddon(){
		if(document.installForm.install_package.value==''){
			alert('<?php echo JText::_('COM_BT_SOCIAL_CONNECT_YOU_NOT_YET_BROWSE_FILE');?> !');
			return;
		}
		document.installForm.submit();
		return;
	}
</script>

<div class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<ul class="nav nav-tabs menu" style="display:none">
			<li class="active"><a href="#details" ><?php echo JText::_('COM_BT_SOCIALCONNECT_DETAIL_WIDGET_MANAGER');?></a></li>
			<li><a href="#installForm"><?php echo JText::_('COM_BT_SOCIAL_WIDGET_UP_IN');?></a></li>			
			<li><a href="#uninstallForm"><?php echo JText::_('COM_BT_SOCIAL_WIDGET_UNUP_IN');?></a></li>			
	</ul>
	<div class="tab-content">
		<div id="details" class="tab-pane active">
			<?php echo $this->loadTemplate('widget');?>
		</div>
		<div id="installForm" class="tab-pane">
			<?php echo $this->loadTemplate('install');?>
		</div>
		<div id="uninstallForm" class="tab-pane">	
			<?php echo $this->loadTemplate('uninstall');?>	
		</div>
	</div>
</div>
