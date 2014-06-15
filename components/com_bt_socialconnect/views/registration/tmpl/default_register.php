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
$captchaField ='';
?>
<div id="register">
	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=registration.register'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
	<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
		<?php $fields = $this->form->getFieldset($fieldset->name);?>
		
		<?php if (count($fields)):?>
			
			<fieldset>		
				<?php if (isset($fieldset->label)):	?>
					<legend><?php echo JText::_($fieldset->label);?></legend>
				<?php endif;?>
			
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
					<div class="control-group">
						<dt><div class="control-label"><?php echo $this->form->getLabel('email2'); ?></div></dt>
						<dd><div class="controls btl-input"><?php echo $this->form->getInput('email2'); ?></div></dd>
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
					<?php foreach($fields as $field):?>
						<?php if(strtolower($field->type)=='captcha'){ $captchaField = $field; continue; } ?>
						<?php if ($field->hidden):?>
						<div class="control-group">
							<div class="controls btl-input">
							<?php echo $field->input;?>
							</div>
						</div>						
						<?php endif;?>
					
					<?php endforeach;?>
				</dl>
				<dl>
						<?php
							echo $this->loadTemplate('userfields');
						?>
				</dl>
				
					<!-- show captcha -->
						<?php if ($captchaField): ?>
						<dl>						
						<div class="control-group">
							<dt>
								<div class="control-label">
									<?php echo $captchaField->label ;?>
								</div>
							</dt>
							<dd><div class="controls" style='height:150px;'><?php echo $captchaField->input;?></div></dd>
						</div>	
						</dl>
						<?php endif;?>
				
			</fieldset>
		<?php endif;?>
	<?php endforeach;?>

			<div class="btn-submit">
				<button type="submit" class="validate btn btn-primary"><span><?php echo JText::_('JSUBMIT'); ?></span></button>			
				<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=registration.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><button type="button" class="btn"><?php echo JText::_('JCANCEL'); ?></button></a>

				<input type="hidden" name="option" value="com_bt_socialconnect" />
				<input type="hidden" name="task" value="registration.register" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</form>
</div>