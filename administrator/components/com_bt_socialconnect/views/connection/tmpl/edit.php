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
		if (task == 'connection.cancel' || document.formvalidator.isValid(document.id('connection-form'))) {
			window.history.back();
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form id="connection-form"  name="adminform" method="POST" action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=connection&layout=edit&id='.$this->item->id); ?>" enctype="multipart/form-data" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
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
		<input type="hidden" name="task"/>
		<?php  echo JHtml::_('form.token'); ?>
</form>