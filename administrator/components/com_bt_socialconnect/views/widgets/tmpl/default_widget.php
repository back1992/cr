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
JHtml::_('behavior.modal');
?>
<?php
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_bt_socialconnect');
$saveOrder = $listOrder == 'w.ordering';
if (!$this->legacy){
	if ($saveOrder)
	{
			$saveOrderingUrl = 'index.php?option=com_bt_socialconnect&task=widgets.saveOrderAjax&tmpl=component';
			JHtml::_('sortablelist.sortable', 'widget-list', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
	}
}
	$sortFields = $this->getSortFields();
	?>	
	<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
	</script>

	<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=widgets');?>" method="post" name="adminForm" id="adminForm">
		<?php if(!$this->legacy): ?>
		<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGETS_SEARCH_IN_NAME'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_SEARCH_IN_NAME'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right">				
					<select name ="filter_widgettype" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_TYPE_FILTER');?></option>
						<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getWidgettype(), 'value', 'text', $this->state->get('filter.widgettype'));?>
					</select>
				</div>
				<div class="btn-group pull-right">				
					<select name="filter_published" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
						<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getPublishedOptions(), 'value', 'text', $this->state->get('filter.published')); ?>
					</select>
				</div>				
								
		</div>
		
		<?php else : ?>	
		
		<fieldset id="filter-bar">
			<div class="filter-search fltlft">
				<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGETS_SEARCH_IN_NAME'); ?>" />
				<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>	
			<div class="filter-select fltrt">
				<select name ="filter_widgettype" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_TYPE_FILTER');?></option>
						<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getWidgettype(), 'value', 'text', $this->state->get('filter.widgettype'));?>
				</select>
			</div>
			<div class="filter-select fltrt">
				<select name="filter_published" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getPublishedOptions(), 'value', 'text', $this->state->get('filter.published')); ?>
				</select>
			</div>			
		</fieldset>	
		<?php endif;?>
		
		<div class="clr"> </div>
		<table class="adminlist table table-striped" id="widget-list">
			<thead>
				<tr>
					<?php if (!$this->legacy): ?>
                            <?php if (isset($this->items[0]->ordering)): ?>
                                <th width="1%" class="nowrap center hidden-phone">
                                    <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'w.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                                </th>
                            <?php endif; ?>
                   <?php endif; ?>
					<th width="1%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'w.title', $listDirn, $listOrder); ?>
					</th>
											
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_WIDGETS_TYPE_LABEL', 'w.wgtype', $listDirn, $listOrder); ?>
					</th>				
					<th width="5%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'w.published', $listDirn, $listOrder); ?>
					</th>
					 <?php if ($this->legacy): ?>
						<th width="12%"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'w.ordering', $listDirn, $listOrder); ?>
						<?php if ($saveOrder) : ?> <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'widgets.saveorder');
							?> <?php endif; ?>
						</th>
					<?php endif; ?>					
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_UPDATE_TIME_LABEL', 'w.updated_time', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'w.id', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_CODE', '', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>		
			<?php foreach ($this->items as $i => $item) :
				$ordering = ($listOrder == 'w.ordering');
				$canCreate	= $user->authorise('core.create',		'com_bt_socialconnect.category.'.$item->id);
				$canEdit	= $user->authorise('core.edit',			'com_bt_socialconnect.widget.'.$item->id);			
				$canEditOwn	= $user->authorise('core.edit.own',		'com_bt_socialconnect.widget.'.$item->id) ;
				$canChange	= $user->authorise('core.edit.state',	'com_bt_socialconnect.widget.'.$item->id);
				?>
				<tr class="row<?php echo $i % 2; ?>">	
					<?php if(!$this->legacy): ?>
					<td class="order nowrap center hidden-phone">
						<?php if ($canChange) :
							$disableClassName = '';
							$disabledLabel	  = '';
							if (!$saveOrder) :
								$disabledLabel    = JText::_('JORDERINGDISABLED');
								$disableClassName = 'inactive tip-top';
							endif; ?>
							<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
								<i class="icon-menu"></i>
							</span>
							<input type="text" style="display:none" name="order[]" size="5"
								value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
						<?php else : ?>
							<span class="sortable-handler inactive" >
								<i class="icon-menu"></i>
							</span>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<?php
						 if ($item->note)
							$note = $item->title . '::' .$item->note;
								else
							$note = $item->title . '::' . JText::_(" ");
					?>
					<td>
						<span class="editlinktip hasTip" title="<?php echo htmlspecialchars($note);?>">
						<?php if ($canEdit || $canEditOwn) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=widget.edit&id='.$item->id);?>">
								<?php echo $this->escape($item->title); ?></a>
						<?php else : ?>
							<?php echo $this->escape($item->title); ?>
						<?php endif; ?>						
						</span>
						<p class="smallsub">
							<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
					</td>								
					<td class="center">
						<?php echo $this->escape($item->wgtype); ?>
					</td>				
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'widgets.', $canChange, 'cb'); ?>
					</td>
					<?php if ($this->legacy): ?>						
					<td class="order"><?php if ($canChange) : ?> <?php if ($saveOrder) : ?>
						<?php if ($listDirn == 'asc') : ?> <span><?php echo $this->pagination->orderUpIcon($i, 1, 'widgets.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?>
															</span> <span><?php echo $this->pagination->orderDownIcon($i, 1, 'widgets.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?>
															</span> <?php elseif ($listDirn == 'desc') : ?> <span><?php echo $this->pagination->orderUpIcon($i, 1, 'widgets.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?>
															</span> <span><?php echo $this->pagination->orderDownIcon($i, 1, 'widgets.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?>
															</span> <?php endif; ?> <?php endif; ?> <?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
															<input type="text" name="order[]" size="5" style="width: 20px"
													   value="<?php echo $item->ordering; ?>" <?php echo $disabled ?>  class="text-area-order" /> <?php else : ?> <?php echo $item->ordering; ?>
						<?php endif; ?>
					 </td>
					<?php endif; ?>
					<td class="center nowrap">
						<?php echo JHtml::_('date', $item->updated_time, JText::_('DATE_FORMAT_LC4')); ?>
					</td>
					<td class="center">
						<?php echo $this->escape($item->id); ?>
					</td>
					<td class="center viewwidget" style="overflow:hidden;border-bottom:1px solid #CCC;height:50px;">
						
						<div class="widgetdemo">								
							<span class="icon-32-new"> </span>
							<div class="imagehover"><a class="modal" rel="{handler: 'iframe', size: {x: 670, y: 350}, onClose: function() {}}" href="<?php JURI::base();?>index.php?option=com_bt_socialconnect&view=widget&layout=edit_getcode&id=<?php echo $this->escape($item->id); ?>&tmpl=component"><img class='getcode'  alt="Demo" src="<?php echo JURI::root() . '/components/com_bt_socialconnect/assets/icon/demo-white-circle-48.png'; ?>"></a><span class="text"> Demo</span></div>															
						</div>
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
