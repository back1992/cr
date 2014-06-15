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
		if (task == 'message.apply' || task == 'message.save' ||task == 'message.save2new'){
			if( document.formvalidator.isValid(document.id('message-form'))){
				if(document.getElementById("jform_hour")!= null){	
					hour = document.getElementById("jform_hour").value;
					if(hour !=''){
						var regexp = /([01][0-9]|[02][0-3]):[0-5][0-9]/;
						var correct = (hour.search(regexp) >= 0) ? true : false;
						
						if(correct ==true){			
							Joomla.submitform(task, document.getElementById('message-form'));
						}
						else{
								alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_HOUR_FAILED'));?>');
								return false;
						}
					}else{
						Joomla.submitform(task, document.getElementById('message-form'));					
					}
				}
				else{
					Joomla.submitform(task, document.getElementById('message-form'));
				}
					
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
				return false;
			}
		}
	}
</script>

<form id="message-form"  name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=message&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<li>						
					<?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?>
				</li>
				<li>						
					<?php echo $this->form->getLabel('message'); ?>
					<?php echo $this->form->getInput('message'); ?>
				</li>
				<li>						
					<?php echo $this->form->getLabel('url'); ?>
					<?php echo $this->form->getInput('url'); ?>
				</li>
				<li>						
					<?php echo $this->form->getLabel('fulltext'); ?>
					<?php echo $this->form->getInput('fulltext'); ?>
				</li>
				<li>						
					<?php echo $this->form->getLabel('image'); ?>
					<?php echo $this->form->getInput('image'); ?>
				</li>
				<li>						
					<?php echo $this->form->getLabel('channel'); ?>
					<?php echo $this->form->getInput('channel'); ?>
				</li>				
				<?php
					$params = JComponentHelper::getParams('com_bt_socialconnect');
					if($params->get('enabled_cronjobs',0)):
				?>
				<li>						
					<?php echo $this->form->getLabel('schedule'); ?>
					<?php echo $this->form->getInput('schedule'); ?>
				</li>
				<?php else: ?>
				<li>						
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</li>
				<?php endif;?>
				<li>						
					<?php echo $this->form->getLabel('id'); ?>
					<?php echo $this->form->getInput('id'); ?>
				</li>
				<li>						
					<?php echo $this->form->getLabel('asset'); ?>
					<?php echo $this->form->getInput('asset'); ?>
				</li>				
				
								
			</ul>
		</fieldset>
	</div>
	<input type="hidden" name="task"/>
	<?php  echo JHtml::_('form.token'); ?>
</form>