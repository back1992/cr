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
// no direct access
defined('_JEXEC') or die;
?>
	<form enctype="multipart/form-data" action="index.php?option=com_bt_socialconnect&view=widgets" method="post" name="installForm">
		<table class="adminform" style="background:none repeat scroll 0 0 #F7F7F7">
			<fieldset id="filter-bar">	
				<label><?php echo JText::_('COM_BT_SOCIAL_WIDGET_UPFILE');?></label>
			</fieldset>
			<tbody>				
				<tr>
					<td width="120">
						<label for="install_package"><?php echo JText::_('COM_BT_SOCIAL_WIDGET_PACFILE');?> :</label>
					</td>
					<td>
						<input id="install_package" class="input_box" type="file" size="57" name="install_package"/>
						<input class="btn btn-primary" type="button" onclick="installAddon()" value="<?php echo JText::_('COM_BT_SOCIAL_WIDGET_UP_IN');?>"/>
					</td>
				</tr>
				
			</tbody>
		</table>
	<input type="hidden" name="task" value="widget.install" />		
	</form>