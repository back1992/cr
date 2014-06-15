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
JHtml::_('behavior.keepalive');
?>

<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif ; ?>

		<?php if($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif ; ?>
	
<div class="login">	

	<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=user.login'); ?>" method="post">

		<fieldset>		
			
				<div class="login-fields btl-input">						
					<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" placeholder="<?php echo JText::_('COM_BTSOCIALCONNECT_FIELD_VALUE_USERNAME') ?>"	 />
				</div>
				<div class="login-fields btl-input">						
					<input id="modlgn-password" class="inputbox" type="password" name="password" alt="password" placeholder="<?php echo JText::_('COM_BTSOCIALCONNECT_FIELD_VALUE_PASSWORD') ?>"	 />
				</div>
					
			<button type="submit" class="btn btn-primary"><?php echo JText::_('COM_BT_SOCIALCONNECT_SIGN_IN'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('loginredirection', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>
<div>
	<ul>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('COM_BT_SOCIALCONNECT_LOGIN_RESET'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('COM_BT_SOCIALCONNECT_LOGIN_REMIND'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=registration'); ?>">
				<?php echo JText::_('COM_BT_SOCIALCONNECT_LOGIN_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
		<li><?php echo Jtext::_('COM_BT_SOCIALCONNECT_SIGN_IN_SOCIAL')?></li>
		<div class ="social_btlogin" style="max-width:500px;">		
		{login_btn:google} 
		{login_btn:facebook} 
		{login_btn:twitter} 
		{login_btn:linkedin} 
		</div>
	</ul>
</div>
