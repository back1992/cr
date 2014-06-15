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
		if (task == 'widget.cancel' || document.formvalidator.isValid(document.id('widget-form'))) {
			Joomla.submitform(task, document.getElementById('widget-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form id="widget-form"  name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=widget&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('JDETAILS'); ?></a></li>
			<li><a href="#options" data-toggle="tab"><?php echo JText::_('JOPTIONS'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="details">
			<div class="width-40 fltlft span6">
				<fieldset class="adminform">				
					<ul class="adminformlist">

					<li><?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?></li>
					
					<li><?php echo $this->form->getLabel('alias'); ?>
					<?php echo $this->form->getInput('alias'); ?></li>			
					
					<li><?php echo $this->form->getLabel('wgtype'); ?>
					<?php echo $this->form->getInput('wgtype'); ?></li>
					
					<li><?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?></li>			

					<li><?php echo $this->form->getLabel('ordering'); ?>
					<?php echo $this->form->getInput('ordering'); ?></li>
					
					<li><?php echo $this->form->getLabel('updated_time'); ?>
					<?php echo $this->form->getInput('updated_time'); ?></li>
					
					<li><?php echo $this->form->getLabel('note'); ?>
					<?php echo $this->form->getInput('note'); ?></li>
					
					<li><?php echo $this->form->getLabel('asset'); ?>
					<?php echo $this->form->getInput('asset'); ?></li>

					<?php if ($this->item->id) : ?>
						<li><?php echo $this->form->getLabel('id'); ?>
						<?php echo $this->form->getInput('id'); ?></li>
					<?php endif; ?>	
					
					
					<div class="clr"></div>	
						<?php if ($this->item->xml) : ?>
						<?php if ($text = trim($this->item->xml->type)) : ?>
							<li><label>
								<?php echo JText::_('COM_BT_SOCIALCONNECT_SOCIAL_WIDGET_TYPE_LABEL'); ?>
								
							</label>
							<span class="readonly mod-desc"><?php echo JText::_($text); ?></span></li>
						<?php endif; ?>
					<?php else : ?>
						<p class="error"><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_ERR_XML'); ?></p>
					<?php endif; ?>
					
					<div class="clr"></div>		
					<?php if ($this->item->xml) : ?>
						<?php if ($text = trim($this->item->xml->description)) : ?>
							<li><label>
								<?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_DESCRIPTION'); ?>
							</label>
							<span class="readonly mod-desc"><?php echo JText::_($text); ?></span></li>
						<?php endif; ?>
					<?php else : ?>
						<p class="error"><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_ERR_XML'); ?></p>
					<?php endif; ?>
					</ul>
				</fieldset>
			</div>
		</div>
		<div class="tab-pane" id="options">
			<div class="width-40 fltlft span6">
				<?php echo JHtml::_('sliders.start', 'module-sliders'); ?>
					
						<?php echo $this->loadTemplate('options'); ?>
						
					<div class="clr"></div>
				<?php echo JHtml::_('sliders.end'); ?>
			</div>
		
			<input type="hidden" name="task"/>
			<?php  echo JHtml::_('form.token'); ?>
		</div>
	</div>
	<div id="bt-contain" class="width-55 fltrt span6">
			<h2><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_PREVIEW') ?></h2>
			<div id="widget-sliders" class="pane-sliders">		
				<?php echo $this->loadTemplate('getcode_preview'); ?>
			</div>		
	</div>
</form>