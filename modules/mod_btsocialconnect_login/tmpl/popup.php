<?php
/**
 * @package 	mod_bt_login - BT Login Module
 * @version		1.1.0
 * @created		April 2012
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2011 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<?php
	$document	= JFactory::getDocument();
	$mainframe = JFactory::getApplication();
	$template = $mainframe->getTemplate();
	if(file_exists(JPATH_BASE.'/templates/'.$template.'/html/mod_btsocialconnect_login/js/default.js'))
	{	
		$document->addScript(JURI::root(true).'templates/'.$template.'/html/mod_btsocialconnect_login/js/default.js');
	}else{
		$document->addScript(JURI::root(true).'/modules/mod_btsocialconnect_login/tmpl/js/default.js');	
	}

?>
<div id="btl">
	<!-- Panel top -->	
	<div class="btl-panel">
		<?php if($type == 'logout') : ?>
		<!-- Avatar -->
		<span class="btl-profile"> 
		<!-- Profile button -->
		<span id="btl-panel-profile" class="btl-dropdown">
			<?php
			if($params->get('avatar')){
				if($avatar):
				?>			
					<span class="input-group-addon"><img style="height:26px" src="<?php echo JURI::root().'images/bt_socialconnect/avatar/'.$avatar; ?>"/></span>
					
				<?php
				endif;
			}
			?>
			<?php
				echo '<span class="welcome">';
				echo JText::_("MOD_BTSOCIALCONNECT_FIELD_WELCOME")." ! ";
				if($params->get('name')) : {
					echo $user->get('username');
				} else : {
					
					echo $user->get('name');
				} endif;
				echo '</span>';
			?>
			<span class="btl-arrow"></span>
		</span>
		<span class="btl-logout">
			<?php if($showLogout == 1):?>
			<div class="btl-buttonsubmit">
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="logoutForm" id="logout-form">
					<button name="Submit" title="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_LOGOUT');?>" class="btl-buttonlogout" onclick="javascript:logout_button_click();return false;"><span class="btn-icon-logout"></span></button>
					<input type="hidden" name="option" value="com_bt_socialconnect" />
					<input type="hidden" name="task" value="user.logout" />
					<input type="hidden" name="return" value="<?php echo base64_encode($return); ?>" />
					<?php echo JHtml::_('form.token'); ?>
				
				</form>
			</div>
			<?php endif;?>
		</span>
		</span>
		<?php else : ?>
			<!-- Login button -->
			<span id="btl-panel-login" href="#btl-content-login" class="<?php echo $effect;?>"><span class="iconlogin"></span><?php echo JText::_('JLOGIN');?></span>
			
			<!-- Registration button -->
			<?php
			if($enabledRegistration){
				$option = JRequest::getCmd('option');
				$task = JRequest::getCmd('task');
				if($option!='com_user' && $task != 'register' ){
			?>
			<span id="btl-panel-registration" href="#btl-content-registration" class="<?php echo $effect;?>"><span class="iconregister"></span><?php echo JText::_('JREGISTER');?></span>
			<?php }
			} ?>
			
			
		<?php endif; ?>
	</div>
	<!-- content dropdown/modal box -->
	<div id="btl-content">
		<?php if($type == 'logout') { ?>
		<!-- Profile module -->
		<div id="btl-content-profile" class="btl-content-block">
			<div class="bt-scroll">
			<div class="bt-scroll-inner">
			<?php if($loggedInHtml): ?>
			<div id="module-in-profile">
				<?php echo $loggedInHtml; ?>
			</div>
			<?php endif; ?>
			
			</div>
			</div>
		</div>
		<?php }else{ ?>	
		<!-- Form login -->	
		<div id="btl-content-login" class="btl-content-block">
			<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
				<?php JHTML::_('script', 'openid.js'); ?>
			<?php endif; ?>
			
			<!-- if not integrated any component -->
			<?php if($moduleRender == ''){?>
			<div id="btl-login-in-process"></div>	
				<h3><?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_LOGIN_TO_YOUR_ACCOUNT') ?></h3>
				<?php if ($enabledRegistration) : ?>
					<div id="register-link">
						<?php echo sprintf(JText::_('MOD_BTSOCIALCONNECT_FIELD_DONT_HAVE_AN_ACCOUNT_YET'),'<a href="'.JRoute::_('index.php?option=com_bt_socialconnect&view=registration').'">','</a>');?>
					</div>
				<?php else: ?>
					<div class="spacer"></div>
				<?php endif; ?>
				<div class="btl-text"><?php echo JText::_('SIGNIN_SOCIAL');?></div>
				<div class="btl-error" id="btl-login-error"></div>			
				<div id="social-connect" class ="social_btlogin" >					
					<div class="bt-social">	
						{login_btn:facebook} 
						{login_btn:twitter} 
						{login_btn:google} 					
						{login_btn:linkedin}
					</div>					
				</div>
				
			<form name="btl-formlogin" id="btl-poplogin" class="btl-formlogin" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post">
				
				<div class="btl-field btl-text"><?php echo JText::_('SIGNIN_SOCIAL_ACCOUNT');?></div>
				<div class="btl-field">					
					<div class="btl-input btl-user">
						<input id="btl-input-username" class="ppfix post user" type="text" name="username" placeholder="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_VALUE_USERNAME') ?>"	/>

					</div>
				</div>
				<div class="btl-field">
				
					<div class="btl-input btl-pass">
						</span><input id="btl-input-password" class="ppfix post pass" type="password" name="password" alt="password" placeholder="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_VALUE_PASSWORD') ?>" />
					</div>
				</div>
				<div class="clear"></div>
				<div class="btl-rsub">
					<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>								
						
						<div id="btl-input-remember">						
								<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_REMEMBER_ME'); ?>
								<input id="btl-checkbox-remember"  type="checkbox" name="remember"
								value="yes" />
						</div>	
					
					<div class="clear"></div>
					<?php endif; ?>
					<div class="btl-buttonsubmit">
						<span class="btl-border"><input type="submit" name="Submit" class="btl-buttonsubmit" onclick="return loginAjax()" value="<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_SIGN_IN') ?>" /> <span class="poin"></span></span>
						<input type="hidden" name="option" value="com_bt_socialconnect" />
						<input type="hidden" name="task" value="user.login" /> 
						<input type="hidden" name="return" id="btl-return"	value="<?php echo base64_encode($return); ?>" />
						<?php echo JHtml::_('form.token');?>
					</div>
				</div>
			</form>
		<div class="btl-reset">			
			<ul id ="bt_ul">
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
					<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_FORGOT_YOUR_PASSWORD'); ?></a>
				</li>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
					<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_FORGOT_YOUR_USERNAME'); ?></a>
				</li>				
			</ul>
		</div>
		<div class="btl-about"> <?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_ABOUT'); ?> </div>	
		<!-- if integrated with one component -->
			<?php }else{ ?>
				<h3><?php echo JText::_('JLOGIN') ?></h3>
				<div id="btl-wrap-module"><?php  echo $moduleRender; ?></div>
				<?php }?>			
		</div>		
			
		<div id="btl-content-registration" class="btl-content-block">			
			<!-- if not integrated any component -->			
						
				<form id="btl-formregistration" name="btl-formregistration" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=registration.register'); ?>" method="post" class="btl-formregistration form-validate" enctype="multipart/form-data"   autocomplete="off">
					<div id="btl-register-in-process"></div>	
					<h3><?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_CREATE_AN_ACCOUNT') ?></h3>
					<div id="btl-success"></div>					
					<div class="bt-scroll">
					<div class="bt-scroll-inner">
					<div class="btl-note"><span><?php echo JText::_("BTL_REQUIRED_FIELD"); ?></span></div>
					<div id="btl-registration-error" class="btl-error"></div>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_NAME' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-name" class="inputbox" type="text" name="jform[name]" aria-required="true" required="required" />
						</div>
					</div>			
					<div class="clear"></div>
					<?php	$config = JComponentHelper::getParams('com_bt_socialconnect');	?>
					<?php	if($config->get('remove_user')): ?>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_EMAIL' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-email1" class="inputbox" type="text" name="jform[email1]" aria-required="true" required="required" />
						</div>
					</div>
					<div class="clear"></div>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_VERIFY_EMAIL' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-email2" type="text" name="jform[email2]" aria-required="true" required="required" />
						</div>
					</div>
					<?php else: ?>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_USERNAME' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-username1" type="text" name="jform[username]" aria-required="true" required="required" />
						</div>
					</div>
					<?php endif; ?>					
					
					<div class="clear"></div>
					
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_PASSWORD' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-password1" type="password" name="jform[password1]" aria-required="true" required="required" />
						</div>
					</div>		
					<div class="clear"></div>
					
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_VERIFY_PASSWORD' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-password2" type="password" name="jform[password2]" aria-required="true" required="required" />
						</div>
					</div>
					<div class="clear"></div>
					<?php	if(!$config->get('remove_user')): ?>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_EMAIL' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-email1" type="text" name="jform[email1]" aria-required="true" required="required" />
						</div>
					</div>
					<div class="clear"></div>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_VERIFY_EMAIL' ); ?><span class="star"> (*)</span></div>
						<div class="btl-input">
							<input id="btl-input-email2" type="text" name="jform[email2]" aria-required="true" required="required" />
						</div>
					</div>
					<?php endif; ?>
					<div class="clear"></div>					
					
					<?php
								include('default_userfields.php');
					?>
					<!-- add captcha-->
					<?php if($enabledRecaptcha=='recaptcha'){?>
					<div class="btl-field">
						<div class="btl-label"><?php echo JText::_( 'MOD_BTSOCIALCONNECT_FIELD_CAPTCHA' ); ?><span class="star"> (*)</span></div>
					</div>
					<div class="btl-field captcha ">
						<div  id="recaptcha"><?php echo $reCaptcha;?></div>
					</div>
					<div id="btl-registration-captcha-error" class="btl-error-detail"></div>
					<div class="clear"></div>
					<!--  end add captcha -->
					<?php }?>
					
					<div class="btl-buttonsubmit">
						<span class="btl-border">					
							<button type="submit" class="btl-buttonsubmit" onclick="return registerAjax()" >
								<?php echo JText::_('MOD_BTSOCIALCONNECT_FIELD_REGISTER');?>							
						</button>
						<span class="add"></span>
						</span>
							 
						<input type="hidden" name="option" value="com_bt_socialconnect" />
						<input type="hidden" name="task" value="registration.register" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
				</div>
				</div>
			</form>
			<!-- if  integrated any component -->
			
		</div>
						
		
	<?php } ?>
	
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
/*<![CDATA[*/
var btlOpt = 
{
	BT_AJAX					:'<?php echo addslashes(JURI::getInstance()->toString()); ?>',
	BT_RETURN				:'<?php echo addslashes($return_decode); ?>',
	RECAPTCHA				:'<?php echo $enabledRecaptcha ;?>',
	LOGIN_TAGS				:'<?php echo $loginTag?>',
	REGISTER_TAGS			:'<?php echo $registerTag?>',
	EFFECT					:'<?php echo $effect?>',
	ALIGN					:'<?php echo $align?>',
	MOUSE_EVENT				:'<?php echo $params->get('mouse_event','click') ;?>',
	LB_SIZE					:'<?php echo $params->get('loginbox_size');?>',
	RB_SIZE					:'<?php echo $params->get('registrationbox_size');?>'
}
if(btlOpt.ALIGN == "center"){
	BTLJ(".btl-panel").css('textAlign','center');
}else{
	BTLJ(".btl-panel").css('float',btlOpt.ALIGN);
}


//BTLJ("input.btl-buttonsubmit,button.btl-buttonsubmit").css({"color":'white',"background":'#f9f9f9'});
//BTLJ("#btl .btl-panel > span").css({"color":'white',"background-color":'#f9f9f9',"border":'white'});
/*]]>*/
</script>
