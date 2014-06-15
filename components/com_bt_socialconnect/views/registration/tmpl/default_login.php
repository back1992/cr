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
 
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

?>
	<div id="login">
	 <fieldset id="users-profile-custom">
		<legend><?php echo JText::_('COM_BT_SOCIALCONNECT_LOGIN_ACCOUNT');?></legend>	
		<form  method="post"  action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=user.login'); ?>" class="form-horizontal" id="login-form" >
				
			<dl>
				<div class="control-group">
					<p id="form-login-username">
						<div class="control-label"><label for="modlgn-username"><?php echo JText::_('COM_BT_SOCIALCONNECT_LOGIN_USERNAME_LABEL') ?></label></div>
						<div class="controls"><input type="text" name="username" class="inputbox"  size="30" /></div>
					</p>
					<p id="form-login-password">
						<div class="control-label"><label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label></div>
						<div class="controls"><input  type="password" name="password" class="inputbox" size="30"  /></div>
					</p>
					<input type="submit" name="Submit" class="btn" value="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_ACCOUNT') ?>" />
				</div>
			</dl>
		
		</form>
		</fieldset>
	</div>