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

// No direct access.
defined('_JEXEC') or die;

$fieldSets = $this->form->getFieldsets('params');
				foreach ($fieldSets as $name => $fieldSet) :
					$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_BT_SOCIALCONNECT_'.$name.'_FIELDSET_LABEL';
					echo JHtml::_('sliders.panel', JText::_($label), $name.'-options');
						if (isset($fieldSet->description) && trim($fieldSet->description)) :
							echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
						endif;
						?>
					<fieldset class="adminform">	
					<?php $hidden_fields = ''; ?>
					<ul class="adminformlist">
						<?php foreach ($this->form->getFieldset($name) as $field) : ?>
						<?php if (!$field->hidden) : ?>
						<li>
							<?php echo $field->label; ?>
							<?php echo $field->input; ?>
						</li>
						<?php else : $hidden_fields.= $field->input; ?>
						<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php echo $hidden_fields; ?>
					</fieldset>
				<?php endforeach; 
?>