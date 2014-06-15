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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHtml::_('behavior.keepalive');
?>
<?php if ($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="logout-form">

		<div class="login-greeting">
		<?php
			if($params->get('avatar')){
				if($avatar):
				?>			
					<img width="100" src="<?php echo JURI::root().'images/bt_socialconnect/avatar/'.$avatar; ?>"/>
					<br/>
				<?php
				endif;
			}
		?>
		<br/>
		<span id="btl-panel-profile" class="btl-dropdown">			
			<?php
			echo JText::_("MOD_BTSOCIALCONNECT_FIELD_WELCOME")." ! ";
			echo'<a href="index.php?option=com_bt_socialconnect&view=profile">';
			if($params->get('name')) : {
				echo $user->get('username');
			} else : {
				
				echo $user->get('name');
			} endif;
			echo'</a>';
			?>
		</span> 
		</div>	
	
	<?php if($showLogout == 1):?>
			<div class="btl-buttonsubmit">
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="logoutForm">
					<button type="submit" class="btn btn-primary" onclick ="javascript:logout_button_click();return false;"><?php echo JText::_('JLOGOUT'); ?></button>
					<input type="hidden" name="option" value="com_bt_socialconnect" />
					<input type="hidden" name="task" value="user.logout" />
					<input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
					<?php echo JHtml::_('form.token'); ?>
				
				</form>
			</div>
			<?php endif;?>
</form>
<?php else : ?>
<div id="btl-content-login" >
<div id="social-connect" class ="social_btlogin" >
	<div class="btl-text"><?php echo JText::_('SIGNIN_SOCIAL');?></div>
		<div class="bt-social">	
			{login_btn:facebook} 
			{login_btn:twitter} 
			{login_btn:google} 					
			{login_btn:linkedin}
		</div>
</div>

<form class="btl-formlogin" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >

	<fieldset class="userdata">
	<div class="btl-field">	
		<div class="btl-input btl-user">		
			<input id="btl-input-username" class="ppfix post user" type="text" name="username" placeholder="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_VALUE_USERNAME') ?>"	/>
		</div>
	</div>
	<div class="btl-field">	
		<div class="btl-input btl-pass">
			<input id="btl-input-password" class="ppfix post pass" type="password" name="password" alt="password" placeholder="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_VALUE_PASSWORD') ?>" />
		</div>
	</div>
	<div class="btl-sub">
		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
		<div id="btl-input-remember">						
				<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_REMEMBER_ME'); ?>
				<input id="btl-checkbox-remember"  type="checkbox" name="remember"
				value="yes" />
			</div>
		<?php endif; ?>
		<div class="btl-buttonsubmit">
			<span class="btl-border">
				<input type="submit" name="Submit" class="btl-buttonsubmit" value="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_SIGN_IN') ?>" /> <span class="poin"></span>
			</span>
			<input type="hidden" name="option" value="com_bt_socialconnect" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
	</fieldset>
	<ul id ="bt_ul">
			<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=registration'); ?>">
				<?php echo JText::_('MOD_BTSOCIALCONNECT_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
		
	</ul>

</form>
</div>

<?php endif; ?>
