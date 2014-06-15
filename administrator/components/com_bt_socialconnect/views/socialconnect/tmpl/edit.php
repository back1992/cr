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
JHTML::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'socialconnect.cancel' || document.formvalidator.isValid(document.id('socialconnect-form'))) {
			Joomla.submitform(task, document.getElementById('socialconnect-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>


<form id="socialconnect-form" name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<a href="#" ></a>	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#userlogin"><?php echo JText::_('COM_BT_SOCIALCONNECT_NO_USER_LOGIN_TITLE');?></a></li>
		<li><a href="#details" ><?php echo JText::_('COM_BT_SOCIALCONNECT_NO_DETAIL_TITLE');?></a></li>
		<li><a href="#groups"><?php echo JText::_('COM_BT_SOCIALCONNECT_ASSIGNED_USER_GROUPS_TITLE');?></a></li>
		<li><a href="#connections"><?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECTIONS_TITLE');?></a></li>		
	</ul>
	
	<div class="tab-content">
		<div id="userlogin" class="tab-pane active">
			<div class="width-20 fltlft span3">
				<fieldset class="image">
				<?php 
				$count = 0;
				$avatar = '';
				foreach ($this->item->user_fields as $key => $el)
				{
				
					if(isset($el->type) && $el->type == 'image'){
						$count ++;
						if($el->value!=''):
							$avatar =  '<img style="max-width:180px;" src='. JURI::root().'images/bt_socialconnect/avatar/'.$el->value.'  />';
							break;
						else:
							$avatar = '<img style="max-width:180px;"  src="' . JURI::root() . '/components/com_bt_socialconnect/assets/img/noimage.png' . '" alt = "' . JText::_("COM_BT_SOCIALCONNECT_NO_IMAGE") . '">';
						endif;
							
					}					
				}
				if($count >0):
					echo $avatar;
				else:
					echo Jtext::_('COM_BT_SOCIALCONNECT_NO_FIELD_IMAGE');
				endif;
				?>
				</fieldset>
			</div>
			<div class="width-80 fltrt span8">
				<fieldset class="adminform">
					<ul class="adminformlist">
						<li><?php echo $this->form->getLabel('id'); ?>
						<?php echo $this->form->getInput('id'); ?></li>
						
						<li><?php echo $this->form->getLabel('user_id'); ?>
						<?php echo $this->form->getInput('user_id'); ?></li>
						
						<li><?php echo $this->form->getLabel('name'); ?>
						<?php echo $this->form->getInput('name'); ?></li>
						
						<?php	$params = JComponentHelper::getParams('com_bt_socialconnect');	?>
						<?php	if($params->get('remove_user')): ?>
							<li><?php echo $this->form->getLabel('email'); ?>
							<?php echo $this->form->getInput('email'); ?></li>
						<?php else:?>
							<li><?php echo $this->form->getLabel('username'); ?>
							<?php echo $this->form->getInput('username'); ?></li>
						<?php endif;?>
						
						<li><?php echo $this->form->getLabel('password'); ?>
						<?php echo $this->form->getInput('password'); ?></li>

						<li><?php echo $this->form->getLabel('password2'); ?>
						<?php echo $this->form->getInput('password2'); ?></li>
						
						<?php	if(!$params->get('remove_user')): ?>
						<li><?php echo $this->form->getLabel('email'); ?>
						<?php echo $this->form->getInput('email'); ?></li>
						<?php endif;?>
						
						<li><?php echo $this->form->getLabel('lastvisitDate'); ?>
						<?php echo $this->form->getInput('lastvisitDate'); ?></li>
						
						<li><?php echo $this->form->getLabel('lastResetTime'); ?>
						<?php echo $this->form->getInput('lastResetTime'); ?></li>

						<li><?php echo $this->form->getLabel('resetCount'); ?>
						<?php echo $this->form->getInput('resetCount'); ?></li>
						
						<li><?php echo $this->form->getLabel('sendEmail'); ?>
						<?php echo $this->form->getInput('sendEmail'); ?></li>
						
						<li><?php echo $this->form->getLabel('block'); ?>
						<?php echo $this->form->getInput('block'); ?></li>
							
						<li><?php echo $this->form->getLabel('groups'); ?>
						<?php echo $this->form->getInput('groups'); ?></li>
						
						<li><?php echo $this->form->getLabel('asset'); ?>
						<?php echo $this->form->getInput('asset'); ?></li>
						
					</ul>
				</fieldset>
			</div>			
		</div>		
		<div id="details" class="tab-pane">
			<div class="width-100 fltlft">
				<fieldset class="adminform">
					<ul class="adminformlist">
						<?php
							echo $this->loadTemplate('userfields');
						?>
					</ul>
				</fieldset>
			</div>
		</div>
		<div id="groups" class="tab-pane">
			<div class="width-100 fltlft">
				<fieldset class="adminform">
					<ul class="adminformlist">
						<li>
							<fieldset id="user-groups" class="adminform">
								<legend><?php echo JText::_('COM_BT_SOCIALCONNECT_ASSIGNED_GROUPS'); ?></legend>
								<?php echo $this->loadTemplate('groups');?>
							</fieldset>
						</li>
					</ul>
				</fieldset>
			</div>
		</div>
		<div id="connections" class="tab-pane">
			<div class="width-100 fltlft">
				<fieldset class="adminform">
					<ul class="adminformlist">
						
						<?php $socialconnect = $this->model->GetSocialconnet($this->item->id);?>
						<?php if(isset($socialconnect) && !empty($socialconnect)){ ?>
							<?php foreach($socialconnect As $social):?>
							<li>
								<input type="text" name='social_type' readonly='true' value='<?php echo $social->social_type ?>'/>
								<span><a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=connection.edit&id='.$social->id);?>"><button type="button" class="btn" value="Submit"><?php echo JText::_('COM_BT_SOCIALCONNECT_VIEW_DETAIL_LABEL'); ?></button>  </a>	</span>						
								<span><a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=connection.delete&id='.$social->id.'&socialId='.$this->item->id);?>"><button type="button" class="btn" value="Submit"> <?php echo JText::_('COM_BT_SOCIALCONNECT_DISCONNECT_LABEL'); ?> </button>  </a></span>
								
							<?php endforeach; ?>							
							
							</li>
						<?php }	else{ ?>						
							<li><?php echo JText::_('COM_BT_SOCIALCONNECT_NO_CONNECTION_TITLE');?></li>
						<?php }?>
						
					</ul>
				</fieldset>
			</div>
		</div>
	</div>	
	
		<input type="hidden" name="task"/>
		<?php  echo JHtml::_('form.token'); ?>
</form>
