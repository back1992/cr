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
$user	= JFactory::getUser();
?>
<div class="logout">		
		<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
	<div class="logout-description">
	<?php endif ; ?>

		<?php if ($this->params->get('logoutdescription_show') == 1) : ?>
			<?php echo $this->params->get('logout_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('logout_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('logout_image')); ?>" class="logout-image" alt="<?php echo JTEXT::_('COM_USER_LOGOUT_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logoutdescription_show') == 1 && str_replace(' ', '', $this->params->get('logout_description')) != '')|| $this->params->get('logout_image') != '') : ?>
	</div>
	<?php endif ; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=user.logout'); ?>" method="post" id="logout-form">
		<div>			
			<img width="100" src="<?php echo JURI::root().'images/bt_socialconnect/avatar/'.$this->model->getAvatar($user->id); ?>"/>		
		<br/>
		<?php if($this->params->get('name') == 0) : {		
			echo JText::sprintf('COM_BT_SOCIACONNECT_HINAME', htmlspecialchars($user->get('name')));
		} else : {
			echo JText::sprintf('COM_BT_SOCIACONNECT_HINAME', htmlspecialchars($user->get('username')));
		} endif; ?>
		<div class="profile" ><a href="index.php?option=com_bt_socialconnect&view=profile">View profile</a></div>
		
			<button type="submit" class="btn btn-primary" onclick ="javascript:logout_button_click();return false;"><?php echo JText::_('JLOGOUT'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('logoutredirection', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
