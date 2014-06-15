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
		if (task == 'channel.cancel' || document.formvalidator.isValid(document.id('channel-form'))) {
			Joomla.submitform(task, document.getElementById('channel-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form id="channel-form"  name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=channel&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('JDETAILS'); ?></a></li>
			<li><a href="#options" data-toggle="tab"><?php echo JText::_('JOPTIONS'); ?></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="details">
			<div class="width-100 fltlft span10">
				<fieldset class="adminform">					
					<ul class="adminformlist">

					<li><?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?></li>
					
					<li><?php echo $this->form->getLabel('alias'); ?>
					<?php echo $this->form->getInput('alias'); ?></li>
					
					<li><?php echo $this->form->getLabel('type'); ?>
					<?php echo $this->form->getInput('type'); ?></li>
					
					<li><?php echo $this->form->getLabel('author'); ?>
					<?php echo $this->form->getInput('author'); ?></li>
					
					<li><?php echo $this->form->getLabel('version'); ?>
					<?php echo $this->form->getInput('version'); ?></li>

					<li><?php echo $this->form->getLabel('description'); ?>
					<?php echo $this->form->getInput('description'); ?></li>
					
					<li><?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?></li>
					

					<li><?php echo $this->form->getLabel('ordering'); ?>
					<?php echo $this->form->getInput('ordering'); ?></li>
					

					
					<li><?php echo $this->form->getLabel('created'); ?>
					<?php echo $this->form->getInput('created'); ?></li>			
				
					
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
								<?php echo JText::_('COM_BT_SOCIALCONNECT_SOCIAL_PUBLISH_TYPE_LABEL'); ?>
								
							</label>
							<span class="readonly mod-desc"><?php echo JText::_($text); ?></span></li>
						<?php endif; ?>
					<?php else : ?>
						<p class="error"><?php echo JText::_('COM_BT_SOCIALCONNECT_PUBLISH_ERR_XML'); ?></p>
					<?php endif; ?>
					
					<div class="clr"></div>		
					<?php if ($this->item->xml) : ?>
						<?php if ($text = trim($this->item->xml->description)) : ?>
							<li><label>
								<?php echo JText::_('COM_BT_SOCIALCONNECT_PUBLISH_DESCRIPTION'); ?>
							</label>
							<span class="readonly mod-desc"><?php echo JText::_($text); ?></span></li>
						<?php endif; ?>
					<?php else : ?>
						<p class="error"><?php echo JText::_('COM_BT_SOCIALCONNECT_PUBLISH_ERR_XML'); ?></p>
					<?php endif; ?>
					</ul>
				</fieldset>
			</div>
		</div>
		<div class="tab-pane" id="options">
			<div class="width-100 fltlft span10">
				<?php echo JHtml::_('sliders.start', 'module-sliders'); ?>
					
						<?php echo $this->loadTemplate('options'); ?>
						
					<div class="clr"></div>
				<?php echo JHtml::_('sliders.end'); ?>
			</div>
			
				<input type="hidden" name="task"/>
				<?php  echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>