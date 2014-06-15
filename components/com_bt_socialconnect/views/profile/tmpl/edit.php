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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//load user_profile plugin language
$lang = JFactory::getLanguage();
$lang->load( 'plg_user_profile', JPATH_ADMINISTRATOR );
?>
<div class="profile-edit">
<?php if ($this->params->get('show_page_heading')) : ?>
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
<?php endif; ?>

<?php if($this->active!=''):
	JError::raiseWarning('', JTEXT::_('COM_BT_SOCIALCONNECT_CONNECT_ACTIVE'));
endif;?>
<div id="menu">
<ul class="nav nav-tabs">
		<li class="active"><a href="#edit-profile"><?php echo JText::_('COM_BT_SOCIALCONNECT_EDIT_PROFILE');?></a></li>
		<li><a href="#socialaccount"><?php echo JText::_('COM_BT_SOCIALCONNECT_ACOUNTCONNECT');?></a></li>					
</ul>
</div>
<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=profile.save'); ?>" method="post"  enctype="multipart/form-data" class="form-validate form-horizontal">
<div class="tab-content">
	<div id="edit-profile" class="tab-pane active">
	
	<?php foreach ($this->form->getFieldsets() as $group => $fieldset):?>
		<?php $fields = $this->form->getFieldset($group);?>
		<?php if (count($fields)):?>
		<fieldset>			
			<dl>
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('name'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('name'); ?></div></dd>
					</div>
					
					<?php	$params = JComponentHelper::getParams('com_bt_socialconnect');	?>
					<?php	if($params->get('remove_user')): ?>
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('email1'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('email1'); ?></div></dd>
					</div>
					<?php else:?>
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('username'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('username'); ?></div></dd>
					</div>
					<?php endif; ?>
					
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('password1'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('password1'); ?></div></dd>
					</div>
					
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('password2'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('password2'); ?></div></dd>
					</div>
					
					<?php	if(!$params->get('remove_user')): ?>
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('email1'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('email1'); ?></div></dd>
					</div>
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('email2'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('email2'); ?></div></dd>
					</div>
					<?php endif; ?>
			
			</dl>
			<dl>
						<?php
							echo $this->loadTemplate('userfields');
						?>
			</dl>			
		</fieldset>
		<?php endif;?>
	<?php endforeach;?>			
		
	</div>

	<div id="socialaccount" class="tab-pane">
	<?php if(!empty($this->social)):?>
		
		<div id="users-profile">				
			<table border="0px" cellspacing="2px" class="tbl_social" >
		<?php foreach ($this->social As $key => $social){		
			switch($social->social_type){ 
				case'facebook':
					if($this->params->get('fbactive')){
					?>	
						<tr><td><?php echo JText::_('COM_BT_SOCIALCONNECT_FACEBOOK_ACCOUNT_SETTING')?></td></tr>
						<tr>							
							<td><a href='index.php?option=com_bt_socialconnect&task=profile.reset&access_token=<?php echo $social->access_token; ?>&user_id=<?php echo $social->user_id ?>&social=facebook'> <div class="btnresc btn-refb"><span class="bg-fb bgsc"><span class="fb-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_UPDATE_PROFILE') ?></span></div></a></td>				
							<td><a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=profile.delete&id='.(int)$social->id.'&socialId='.$social->social_id);?>"><div class="btnresc btn-refb"><span class="bg-fb bgsc"><span class="fb-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_DISCONNECT_LABEL') ?></span></div>  </a> </td>
							<td>
							<?php if($social->enabled_publishing ==1){
								$checked='checked';
							  }else{
								$checked='';
							  }							
								echo'<input type="hidden" '.$checked.' name="jform[enabled_publishing]['.'facebook'.']" value="0"/>';
								echo'<input type="checkbox" '.$checked.' name="jform[enabled_publishing]['.'facebook'.']" value="1"/> '.JTEXT::_('COM_BT_SOCIALCONNECT_ENABLED_PUBLISHING');
							?>
							</td>
						</tr>
										
					<?php
					}
					break;
				case'twitter':
					if($this->params->get('ttactive')){
					?>
					<tr><td><?php echo JText::_('COM_BT_SOCIALCONNECT_TWITTER_ACCOUNT_SETTING')?></td></tr>
					<tr>
						
						<td><a href='index.php?option=com_bt_socialconnect&task=profile.reset&access_token=<?php echo $social->access_token; ?>&user_id=<?php echo $social->user_id ?>&social=twitter'> <div class="btnresc btn-refb"><span class="bg-tt bgsc"><span class="tt-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_UPDATE_PROFILE') ?></span></div></a></td>	
						<td><a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=profile.delete&id='.(int)$social->id.'&socialId='.$social->social_id);?>"><div class="btnresc btn-refb"><span class="bg-tt bgsc"><span class="tt-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_DISCONNECT_LABEL') ?></span></div>  </a></td>
						<td>
							<?php if($social->enabled_publishing ==1){
								$checked='checked';
							}else{
								$checked='';
							}
							echo'<input type="hidden" '.$checked.' name="jform[enabled_publishing]['.'twitter'.']" value="0"/>';
							echo'<input type="checkbox" '.$checked.' name="jform[enabled_publishing]['.'twitter'.']" value="1"/> '.JTEXT::_('COM_BT_SOCIALCONNECT_ENABLED_PUBLISHING');
							?>
						</td>						
					</tr>
					
					<?php
					}
					break;
				case'google':				
					if($this->params->get('ggactive')){
					?>	
					<tr><td><?php echo JText::_('COM_BT_SOCIALCONNECT_GOOGLE_ACCOUNT_SETTING')?></td></tr>
					<tr>
						
						<td><a href='index.php?option=com_bt_socialconnect&task=profile.reset&access_token=<?php echo $social->access_token;?>&user_id=<?php echo $social->user_id ?>&social=google'> <div class="btnresc btn-refb"><span class="bg-gg bgsc"><span class="gg-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_UPDATE_PROFILE') ?></span></div></a></td>				
						<td><a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=profile.delete&id='.(int)$social->id.'&socialId='.$social->social_id);?>"><div class="btnresc btn-refb"><span class="bg-gg bgsc"><span class="gg-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_DISCONNECT_LABEL') ?></span></div></a></td>
						<td>
							<?php if($social->enabled_publishing ==1){
								$checked='checked';
							}else{
								$checked='';
							}
							echo'<input type="hidden" '.$checked.' name="jform[enabled_publishing]['.'google'.']" value="0"/>';
							echo'<input type="checkbox" '.$checked.' name="jform[enabled_publishing]['.'google'.']" value="1"/> '.JTEXT::_('COM_BT_SOCIALCONNECT_ENABLED_PUBLISHING');
							?>
						</td>
					</tr>					
					<?php
					}
					break;
				case'linkedin':				
					if($this->params->get('linkactive')){
					?>	
					<tr><td><?php echo JText::_('COM_BT_SOCIALCONNECT_LINKEDIN_ACCOUNT_SETTING')?></td></tr>
					<tr>
						
						<td><a href='index.php?option=com_bt_socialconnect&task=profile.reset&access_token=<?php echo $social->access_token;?>&user_id=<?php echo $social->user_id ?>&social=linkedin'> <div class="btnresc btn-refb"><span class="bg-in bgsc"><span class="in-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_UPDATE_PROFILE') ?></span></div></a></td>				
						<td><a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=profile.delete&id='.(int)$social->id.'&socialId='.$social->social_id);?>"><div class="btnresc btn-refb"><span class="bg-in bgsc"><span class="in-icon sc-icon"></span></span><span class="text-resc"><?php echo JText::_('COM_BT_SOCIALCONNECT_DISCONNECT_LABEL') ?></span></div> </a></td>
						<td>
							<?php if($social->enabled_publishing ==1){
								$checked='checked';
							}else{
								$checked='';
							}
							echo'<input type="hidden" '.$checked.' name="jform[enabled_publishing]['.'linkedin'.']" value="0"/>';
							echo'<input type="checkbox" '.$checked.' name="jform[enabled_publishing]['.'linkedin'.']" value="1"/> '.JTEXT::_('COM_BT_SOCIALCONNECT_ENABLED_PUBLISHING');
							?>
						</td>
					</tr>					
					<?php
					}
					break;
				
			} 	
		}
		?>
		</table>	
		</div>	
	<?php endif;?>	
	
	<?php if($this->newconnect){  ?>	
		<div id="users-profile">				
					<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT'); ?>
				
			
				<div class ="social_btlogin" style="padding: 8px 0px 40px; max-width:500px;width:100%">	
					<?php 			
						echo ($this->newconnect);
					?>
				</div>	
			
		</div>
	<?php }?>
	
	</div>
	<div class="btn-submit">
		<button type="submit" class="validate btn btn-primary"><span><?php echo JText::_('JSUBMIT'); ?></span></button>				
		<a href="<?php echo JRoute::_(''); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><button class="btn" type="button"><?php echo JText::_('JCANCEL'); ?></button></a>
			<input type="hidden" name="option" value="com_bt_socialconnect" />
			<input type="hidden" name="task" value="profile.save" />
			<?php echo JHtml::_('form.token'); ?>	
	</div>
</div>
</form>
</div>