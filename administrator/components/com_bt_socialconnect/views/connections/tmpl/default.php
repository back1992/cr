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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=connections');?>" method="post" name="adminForm" id="adminForm">
<?php if(!$this->legacy): ?>
	<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_SEARCH_IN_NAME'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_SEARCH_IN_NAME'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right">				
				<select name ="filter_socialtype" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_SOCIAL_TYPE_FILTER');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getConnections(), 'value', 'text', $this->state->get('filter.socialtype'));?>
				</select>
			</div>				
			
	</div>
<?php else : ?>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>		
		<div class="filter-select fltrt">
			<select name ="filter_socialtype" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_SOCIAL_TYPE_FILTER');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getConnections(), 'value', 'text', $this->state->get('filter.socialtype'));?>
			</select>
		</div>
	</fieldset>
	
<?php endif;?>	
	<div class="clr"> </div>
	<table class="adminlist table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_NAME_LABEL', 'u.name', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_ID_LABEL', 'c.social_id', $listDirn, $listOrder); ?>
				</th>							
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_TYPE_LABEL', 'c.social_type', $listDirn, $listOrder); ?>
				</th>							
				<th width="5%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_CREATE_TIME_LABEL', 'c.created_time', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_USER_ID_LABEL', 'c.user_id', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_ID_LABEL', 'c.id', $listDirn, $listOrder); ?>
				</th>				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>		
		<?php foreach ($this->items as $i => $item) :
	
			$canCreate	= $user->authorise('core.create',		'com_bt_socialconnect.category.'.$item->id);
			$canEdit	= $user->authorise('core.edit',			'com_bt_socialconnect.connection.'.$item->id);			
			$canEditOwn	= $user->authorise('core.edit.own',		'com_bt_socialconnect.connection.'.$item->id) ;
			$canChange	= $user->authorise('core.edit.state',	'com_bt_socialconnect.connection.'.$item->id);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>			
				<td>
					
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=connection.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->name); ?>
					<?php endif; ?>						
						
				</td>
				<td class="center">
					<?php echo $this->escape($item->social_id); ?>
				</td>				
				<td class="center">
					<?php echo $this->escape($item->social_type); ?>
				</td>								
				<td class="center">
					<?php echo $this->escape($item->created_time); ?>
				</td>							
				<td class="center">
					<?php echo $this->escape($item->user_id); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->id); ?>
				</td>
				
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>	

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
