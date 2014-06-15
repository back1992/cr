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
// no direct access

defined('_JEXEC') or die;

?>

	<form enctype="multipart/form-data" action="index.php?option=com_bt_socialconnect&view=widgets" method="post" name="uninstallForm">
		<table class="adminlist table table-striped">
			<div class="clr"></div>
			<fieldset id="filter-bar">		
				<button type="button" class="btn btn-primary" onclick='document.uninstallForm.submit();'><?php echo JText::_('COM_BT_SOCIALCONNECT_UNINSTALL_WIDGET'); ?></button>
			</fieldset>
			<div class="clr"></div>
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>					
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_NAME_LABEL', 'w.name', '', ''); ?>
					</th>						
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_WIDGET_AUTHOR_LABEL', 'w.author', '', ''); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_WIDGET_VERSION_LABEL', 'w.version', '', ''); ?>
					</th>	
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_WIDGET_DATE_LABEL', 'w.date', '', ''); ?>
					</th>							
					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>		
			<?php foreach ($this->folder as $i => $item) :				
				?>
				<tr class="row<?php echo $i % 2; ?>">	
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->type); ?>
					</td>					
					<td>					
							<?php echo $this->escape($item->title); ?>						
					</td>
									
					<td class="center">
						<?php echo $this->escape($item->xml->author); ?>
					</td>	
					<td class="center">
						<?php echo $this->escape($item->xml->version); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->xml->creationDate); ?>
					</td>				
					
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
			<input type="hidden" name="task" value="widget.uninstall" />	
	</form>