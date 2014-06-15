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
<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=socialconnects');?>" method="post" name="adminForm" id="adminForm">
	<?php if(!$this->legacy): ?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_BT_SOCIALCONNECT_USER_SEARCH_IN_NAME'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_SEARCH_IN_NAME'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>			
			<div class="btn-group pull-right hidden-phone">				
				<select name="filter_group_id" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_FILTER_USERGROUP');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getGroups(), 'value', 'text', $this->state->get('filter.group_id'));?>
				</select>
			</div>
			<div class="btn-group pull-right hidden-phone">				
				<select name ="filter_connection" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_FILTER_CONNECTION');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getConnections(), 'value', 'text', $this->state->get('filter.connection'));?>
				</select>
			</div>			
			
		</div>
	<?php else : ?>
	
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_USER_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name ="filter_connection" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_FILTER_CONNECTION');?></option>
				<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getConnections(), 'value', 'text', $this->state->get('filter.connection'));?>
			</select>
			<select name="filter_group_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_FILTER_USERGROUP');?></option>
				<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getGroups(), 'value', 'text', $this->state->get('filter.group_id'));?>
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
				<?php	$field = $this->model->getFieldName();?>	
				<?php	$r = $this->model->getTitle();?>	
				<?php foreach ($r as $e): ?>				
				<?php 	if (array_key_exists($e->alias, $field)):?>
						<th width="10%" class="nowrap">
							<?php echo JHtml::_('grid.sort', $e->name, 'a.'.$e->alias, $listDirn, $listOrder); ?>
						</th>
				<?php endif ;?>							
										
				<?php endforeach;?>		
				<th class="nowrap" width="10%">
					<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_HEADING_GROUPS'); ?>
				</th>					
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_USER_ID_LABEL', 'u.id', $listDirn, $listOrder); ?>
				</th>
				
			</tr>
		</thead>
		
		<tfoot>
			<tr>
				<td colspan="<?php echo(count($r) +4); ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>		
		<?php foreach ($this->items as $i => $item) :	
			$array = (array) $item;			
			
			$canCreate	= $user->authorise('core.create',		'com_bt_socialconnect.category.'.$item->id);
			$canEdit	= $user->authorise('core.edit',			'com_bt_socialconnect.socialconnect.'.$item->id);			
			$canEditOwn	= $user->authorise('core.edit.own',		'com_bt_socialconnect.socialconnect.'.$item->id) ;
			$canChange	= $user->authorise('core.edit.state',	'com_bt_socialconnect.socialconnect.'.$item->id);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>					
				
				<td>
					
					<?php if ($canEdit || $canEditOwn) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=socialconnect.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->name); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->name); ?>
					<?php endif; ?>	
									
				</td>				
				<?php	$r =$this->model->getTitle();?>	
				<?php foreach ($r as $e): ?>
					<?php 	if (array_key_exists($e->alias, $array)):?>				
								<?php if($e->type =='image') :								
										if ($array[$e->alias]){
											$avatar = '<img width="80" src="' . JURI::root().'images/bt_socialconnect/avatar/'. $array[$e->alias] . '" alt = "' . $array[$e->alias] . '">';
										}
										else{
											$avatar = '<img style="width:80px; opacity:0.8"  src="' . JURI::root() . '/components/com_bt_socialconnect/assets/img/noimage.png' . '" alt = "' . JText::_("COM_BT_SOCIALCONNECT_NO_IMAGE") . '">';
										}
								?>
								<td class="center">
									<?php echo ($avatar); ?>
								</td>
							<?php elseif($e->type =='text') :?>
								<td class="center">
									<span class="editlinktip hasTip" title="<?php echo $this->escape(strip_tags($array[$e->alias])); ?>">
										<?php
											$string = $this->escape(strip_tags($array[$e->alias]));
											if (strlen($string) > 50) {
												$stringCut = substr($string, 0, 50);
												
												$string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
											}
											echo $string;											
										?>
									</span>
								</td>
							<?php else :?>
								<?php if($e->type =='date'):
									if($array[$e->alias] ==''|| $array[$e->alias]=='0000-00-00'){
										$array[$e->alias]= null;
									}
									endif;
								?>
								<td class="center">
									<?php echo $this->escape(strip_tags($array[$e->alias])); ?>
								</td>	
							<?php endif; ?>
					<?php endif ;?>							
				<?php endforeach;?>	
				
				<td class="center">
					<?php if (substr_count($item->group_names, "\n") > 1) : ?>
						<span class="hasTip" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_HEADING_GROUPS').'::'.nl2br($item->group_names); ?>"><?php echo JText::_('COM_BT_SOCIALCONNECT_USERS_MULTIPLE_GROUPS'); ?></span>
					<?php else : ?>
						<?php echo nl2br($item->group_names); ?>
					<?php endif; ?>
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
