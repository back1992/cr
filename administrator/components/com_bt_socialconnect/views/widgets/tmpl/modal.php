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
// No direct access to this file

defined('_JEXEC') or die('Restricted Access');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';
$jinput = JFactory::getApplication()->input;
$function = $jinput->get('function', 'jSelectWidget', 'CMD');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=widgets&layout=modal&tmpl=component&function='.$function.'&'.JSession::getFormToken().'=1');?>'); ?>"
	method="post" name="adminForm" id="adminForm" class="form-inline">
	
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
					<select name ="filter_widgettype" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_TYPE_FILTER');?></option>
						<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getWidgettype(), 'value', 'text', $this->state->get('filter.widgettype'));?>
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
				<select name ="filter_widgettype" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_WIDGET_TYPE_FILTER');?></option>
						<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getWidgettype(), 'value', 'text', $this->state->get('filter.widgettype'));?>
				</select>
			</div>	
		</fieldset>	
		<?php endif;?>

	<div class="clr"></div>
		<button type="button" onclick='getCheckedValue(document.getElementsByName("cid[]"));'><?php echo JText::_('COM_BT_SOCIALCONNECT_INSERT'); ?></button>
	<div class="clr"></div>

	<table class="adminlist">
		<thead>
			<tr>	
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'w.title', $listDirn, $listOrder); ?>
				</th>										
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_WIDGETS_TYPE_LABEL', 'w.type', $listDirn, $listOrder); ?>
				</th>		
				
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_UPDATE_TIME_LABEL', 'w.updated_time', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'w.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>

		</tfoot>
		<tbody>
		<?php

		foreach ($this->showitems as $i => $item) :	

		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->alias); ?>
				</td>
				<?php
					 if ($item->note)
                        $note = $item->title . '::' .$item->note;
							else
                        $note = $item->title . '::' . JText::_(" ");
				?>
				<td><span class="editlinktip hasTip" title="<?php echo htmlspecialchars($note);?>">
				<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>(new Array('<?php echo $this->escape(addslashes($item->alias)); ?>'));">
					<?php echo $this->escape($item->title); ?> </a>
					
					</span>
				</td>				
							
				<td class="center">
					<?php echo $this->escape($item->wgtype); ?>
				</td>						
				<td class="center nowrap">
					<?php echo JHtml::_('date', $item->updated_time, JText::_('DATE_FORMAT_LC4')); ?>
				</td>
				<td class="center">
					<?php echo $this->escape($item->id); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" /> <input type="hidden"
			name="boxchecked" value="0" /> <input type="hidden"
			name="filter_order" value="<?php echo $listOrder; ?>" /> <input
			type="hidden" name="filter_order_Dir"
			value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<script type="text/javascript">
	function getCheckedValue(name){
		var alias = new Array();
		 for(var i = 0; i < name.length; i++)
		   {
			  if(name[i].checked)
			  {			
				alias.push(name[i].value);			
				
			  }
		   }
		   if (window.parent) window.parent.<?php echo $this->escape($function);?>(alias);
	}
</script>
