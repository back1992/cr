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
		if (task == 'userfield.cancel' || document.formvalidator.isValid(document.id('userfield-form'))) {
			Joomla.submitform(task, document.getElementById('userfield-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form id="userfield-form"  name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=userfield&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
<ul class="nav nav-tabs">
		<li class="active"><a href="#detail"><?php echo JText::_('COM_BT_SOCIALCONNECT_USERFIELD_DETAIL_TITLE');?></a></li>
		
		<li><a href="#assign"><?php echo JText::_('COM_BT_SOCIALCONNECT_USERFIELD_ASSIGN_TITLE');?></a></li>		
	</ul>
	<div class="tab-content">
	<div id="detail" class="tab-pane active">
		<div class="width-100 fltlft">
				<fieldset class="adminform">
					<ul class="adminformlist">
						<?php foreach($this->form->getFieldset('detail')As $field): ?>
							<li>
						
								<?php
								echo $field->label;
								if ($field->type == "Editor")
									echo '<div class="clr"></div>';
								echo $field->input;
								?>
							</li>
						<?php endforeach; ?>
						
					</ul>
				</fieldset>
		</div>
	</div>
	<div id="assign" class="tab-pane">
		<div class="width-100 fltlft">
		<fieldset class="adminform">
					<ul class="adminformlist">
						<?php foreach($this->form->getFieldset('assign')As $field): ?>
							<li>
							
								<?php
								echo $field->label;
								if ($field->type == "Editor")
									echo '<div class="clr"></div>';
								echo $field->input;
								?>
							</li>
						<?php endforeach; ?>
						
					</ul>
				</fieldset>
		</div>
	</div>
	</div>
		<input type="hidden" name="task"/>
		<?php  echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	switchType('<?php echo $this->item->type?>');
	function switchType(type){		
		jQuery("#jform_type_image-lbl").parent().hide();
		jQuery("#jform_type_text-lbl").parent().hide();
		jQuery("#jform_type_string-lbl").parent().hide();
		jQuery("#jform_type_date-lbl").parent().hide();		
		jQuery("#jform_type_dropdown-lbl").parent().hide();
		jQuery('#jform_type_'+type+'-lbl').parent().fadeIn();
	}
</script>