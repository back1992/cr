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
		if (task == 'message.cancel') {
			Joomla.submitform(task, document.getElementById('message-form'));
		}
		else {		
			if (task == 'message.apply' || task == 'message.save'){
			
					if(document.getElementById("jform_hour")!= null){						
						hour = document.getElementById("jform_hour").value;
						if(hour!=''){
							var regexp = /([01][0-9]|[02][0-3]):[0-5][0-9]/;
							var correct = (hour.search(regexp) >= 0) ? true : false;
							
							if(correct ==true){			
								Joomla.submitform(task, document.getElementById('message-form'));
							}
							else{
									alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_HOUR_FAILED'));?>');
									return false;
							}
						}
						else{
							Joomla.submitform(task, document.getElementById('message-form'));
						}
				}
				else{
						Joomla.submitform(task, document.getElementById('message-form'));
				}
			
			}
			
		}
	}
</script>

<form id="message-form"  name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=message&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<fieldset class="adminform">
		<table class="adminlist adminformlist table-striped width-100">
		<tbody>			
			<tr>
				<td>
					<?php echo $this->form->getLabel('createdby'); ?>
				</td>
				<td>
					<label><?php echo JText::_($this->item->createdby); ?></label>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->form->getLabel('type'); ?>
				</td>
				<td>
					<label><?php echo JText::_($this->item->type); ?></label>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->form->getLabel('title'); ?>
				</td>
				<td>
					<label><?php echo JText::_($this->item->title); ?></label>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->form->getLabel('url'); ?>
				</td>
				<td>
					<label><?php echo JText::_($this->item->url); ?></label>
				</td>
			</tr>			
			<tr>
				<td>
					<?php echo $this->form->getLabel('message'); ?>
				</td>
				<td style="background:lavender; border:1px solid snow;">
					<?php echo JText::_($this->item->message); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->form->getLabel('trigger'); ?>
				</td>
				<td>
					<?php echo $this->item->trigger; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->form->getLabel('log'); ?>
				</td>
				<td>
					<?php echo JText::_($this->item->log); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->form->getLabel('event'); ?>
				</td>
				<td>
					<?php echo JText::_($this->item->event); ?>
				</td>
			</tr>
			<?php
				$params = JComponentHelper::getParams('com_bt_socialconnect');
				if($params->get('enabled_cronjobs',0)):
			?>
			<tr>
				<td>
					<?php echo $this->form->getLabel('schedule'); ?>
				</td>
				<td>
					<?php echo $this->form->getInput('schedule'); ?>
				</td>
			</tr>
			<?php endif;?>
			<tr>
				<td>
					<?php echo $this->form->getLabel('published'); ?>
				</td>
				<td>
					<?php echo $this->form->getInput('published'); ?>
				</td>
			</tr>			
			<tr>
				<td>
					<?php echo $this->form->getLabel('sent_time'); ?>
				</td>
				<td>
					<?php echo JText::_($this->item->sent_time); ?>
				</td>
			</tr>	
			<tr>
				<td>
					<?php echo $this->form->getLabel('created_time'); ?>
				</td>
				<td>
					<?php echo JText::_($this->item->created_time); ?>
				</td>
			</tr>
			<tr>						
				<td><?php echo $this->form->getLabel('asset'); ?></td>
				<td><?php echo $this->form->getInput('asset'); ?></td>
			</tr>
			<tr>						
				<td><?php echo $this->form->getLabel('id'); ?></td>
				<td><?php echo $this->form->getInput('id'); ?></td>
			</tr>	
		</tbody>
		</table>
		</fieldset>
	<input type="hidden" name="task"/>
	<?php  echo JHtml::_('form.token'); ?>
</form>