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
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'message.delete') {
			if (confirm("Do you like delete all message?")) {
				Joomla.submitform(task, document.getElementById('adminForm'));
			}
		}
		else{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
		
	}
</script>
<div class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
<form action="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&view=messages');?>" method="post" name="adminForm" id="adminForm">
	<?php if(!$this->legacy): ?>
	<div id="filter-bar" class="btn-toolbar">	
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SEARCH_IN_NAME'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_CONNECT_SEARCH_IN_NAME'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>						
			<div class="btn-group pull-right">	
				<select name="filter_published" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getStatusOptions(), 'value', 'text', $this->state->get('filter.published'));?>
				</select>
			</div>
			<div class="btn-group pull-right">				
				<select name ="filter_trigger" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_TRIGGER_FILTER');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getTriggerOptions(), 'value', 'text', $this->state->get('filter.trigger'));?>
				</select>
			</div>			
			
			<div class="btn-group pull-right">				
				<select name ="filter_socialtype" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SOCIAL_TYPE_FILTER');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getSocialtype(), 'value', 'text', $this->state->get('filter.socialtype'));?>
				</select>
			</div>
			<div class="btn-group pull-right">				
				<select name ="filter_socialpost" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SOCIAL_POST_FILTER');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getSocialpost(), 'value', 'text', $this->state->get('filter.socialpost'));?>
				</select>
			</div>
		</div>
	<?php else : ?>
	<fieldset id="filter-bar">	
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
							
			<select name ="filter_trigger" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_TRIGGER_FILTER');?></option>
					<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getTriggerOptions(), 'value', 'text', $this->state->get('filter.trigger'));?>
			</select>				
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getStatusOptions(), 'value', 'text', $this->state->get('filter.published'));?>
			</select>
		</div>
		<div class="filter-select fltrt">
				<select name ="filter_socialtype" class="inputbox" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SOCIAL_TYPE_FILTER');?></option>
						<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getSocialtype(), 'value', 'text', $this->state->get('filter.socialtype'));?>
				</select>
		</div>
		<div class="filter-select fltrt">
			<select name ="filter_socialpost" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_BT_SOCIALCONNECT_MESSAGE_SOCIAL_POST_FILTER');?></option>
				<?php echo JHtml::_('select.options', Bt_SocialconnectHelper::getSocialpost(), 'value', 'text', $this->state->get('filter.socialpost'));?>
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
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_ARTICLE_LABEL', 'm.title', $listDirn, $listOrder); ?>
				</th>
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_CHANNEL_NAME_LABEL', '', $listDirn, $listOrder); ?>
				</th>					
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_CHANNEL_TYPE_LABEL', 'm.type', $listDirn, $listOrder); ?>
				</th>	
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_EVENT_LABEL', 'm.event', $listDirn, $listOrder); ?>
				</th>				
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SOCIAL_TRIGGER_LABEL', 'm.trigger', $listDirn, $listOrder); ?>
				</th>							
				<th width="5%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JSTATUS', 'm.published', $listDirn, $listOrder); ?>
				</th>			
				<th width="5%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SENT_TIME_LABEL', 'm.sent_time', $listDirn, $listOrder); ?>
				</th>				
				<th width="10%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SEND_BY_LABEL', 'm.createdby', $listDirn, $listOrder); ?>
				</th>
				<?php
					$params = JComponentHelper::getParams('com_bt_socialconnect');
					if($params->get('enabled_cronjobs',0)):
				?>
				<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_SCHEDULE_LABEL', '', $listDirn, $listOrder); ?>
				</th>
				<?php endif;?>
				<th width="10%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'COM_BT_SOCIALCONNECT_RENEW_LABEL', '', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>		
		<?php foreach ($this->items as $i => $item) :
	
			$canCreate	= $user->authorise('core.create',		'com_bt_socialconnect.category.'.$item->id);
			$canEdit	= $user->authorise('core.edit',			'com_bt_socialconnect.message.'.$item->id);			
			$canEditOwn	= $user->authorise('core.edit.own',		'com_bt_socialconnect.message.'.$item->id) ;			
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				
				<td>
					<span class="editlinktip hasTip" title="<?php echo  htmlspecialchars($item->message); ?>">
					<?php if ($canEdit || $canEditOwn) : ?>						
						<a href="<?php echo JRoute::_('index.php?option=com_bt_socialconnect&task=message.edit&id='.$item->id);?>">
							<?php echo $this->escape($item->title); ?></a>
						<?php else :?>
						<?php echo $this->escape($item->title); ?>											
					<?php endif; ?>	
					</span>
						
				</td>
				<td class="center">
					
						<?php	$field = $this->model->getNameMessage($item->message_type,$item->chanel_id);?>
						<?php echo '<a href="'.$field['link'].'" target="_blank">'.$field['name'].'</a>'?>
					
				</td>
				<td class="center">
					<?php if($this->escape($item->type)): ?>
						<?php echo $this->escape($item->type); ?>
					<?php else: ?>
						<?php echo JText::_('COM_BT_SOCIALCONNECT_SYSTEM_CREATE'); ?>
					<?php endif; ?>
				</td>				
				<td class="center">
					<?php echo $this->escape($item->event); ?>
				</td>	
				<td class="center">
					<?php echo $this->escape($item->trigger); ?>
				</td>				
				<td class="center">
				<?php
					switch($item->published){
						case 1:
							echo'<span class="bt-submited"><span class="white-text">'.Jtext::_('COM_BT_SOCIALCONNECT_SUBMITED_CPANEL').'</span></span>';
							break;
						case 2:
							echo'<span class="bt-pending white-text pending'.$item->id.'" >'.Jtext::_('COM_BT_SOCIALCONNECT_PENDING_CPANEL').'</span>';
							break;
						default:
							echo'<span class="bt-error white-text error'.$item->id.'"><span class="text">'.Jtext::_('COM_BT_SOCIALCONNECT_ERROR_CPANEL').'</span></span>';
							break;
					}						
				?>					
				</td>							
				<td class="center">
					<div class="senttime<?php echo $item->id;?>">
					<?php echo $this->escape($item->sent_time); ?>
					</div>
				</td>
				<td class="center">
					<?php echo $this->escape($item->createdby); ?>
				</td>
				<?php
					$params = JComponentHelper::getParams('com_bt_socialconnect');
					if($params->get('enabled_cronjobs',0)):
				?>
				<td class="center" nowrap="nowrap">
					<?php $date=(object)unserialize($item->schedule); ?>
					<?php if(isset($date->date)) :?>
						<?php echo $date->date; ?>					
					<?php endif; ?>
					<?php if(isset($date->hour)) :?>
						<?php echo ' '.$date->hour; ?>					
					<?php endif; ?>
					
				</td>
				<?php endif;?>
				<td class="center">					
					<div class="btn-submited" rel="<?php echo $item->published; ?>">
						<?php echo '<button class="btn-button btn-disable" rel='.$item->id.'><span>' . JText::_('Submit') . '<span></button>';?>
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
</div>
<script type ="text/javascript">
	jQuery=jQuery.noConflict();
	jQuery( document ).ready(function() {
		
		jQuery.each(jQuery('.btn-submited'),function(){
			var check =jQuery(this).attr('rel');
			if(check !=1){
				jQuery(this).find('.btn-button').addClass('btn-socialsend');
				jQuery(this).find('.btn-button').removeClass('btn-disable');
			}
			
		});
		jQuery('.btn-disable').click(function(){
			return false;
		});
		jQuery.each(jQuery('.btn-socialsend'),function(){
			var self = jQuery(this);
			var id=jQuery(this).attr('rel');
			
			jQuery(this).click(function(){			
				url =location.href;		
				url = url.replace('&view=messages','');		
				var id= jQuery(this).attr('rel');		
				jQuery.ajax({
					url: url,
					data: "task=message.send&id="+id,
					type: "post",
					beforeSend: function(){
						self.find('span').animate({opacity: 0},100, function(){
							jQuery(this).text('<?php echo JText::_('Sending...')?>').addClass('bt-loading').animate({opacity: 1},100).delay(1000);
						});
					},
					success: function(response){
						var data = jQuery.parseJSON(response);						
						self.find('span').animate({opacity: 0},100, function(){
							jQuery(this).text(data.message).animate({opacity: 1},100)
							.delay(1000).removeClass('bt-loading')
							.animate({opacity: 0},100, function(){
								jQuery(this).text('<?php echo JText::_('Submit')?>').css('color','#FFFFFF').animate({opacity: 1},100);								
								if(data.success){
									jQuery('.pending'+id).text('<?php echo Jtext::_('COM_BT_SOCIALCONNECT_SUBMITED_CPANEL') ?>'); 								
									jQuery('.pending'+id).removeClass('bt-pending');
									jQuery('.pending'+id).addClass('bt-submited');				
									//Set error
									jQuery('.error'+id).text('<?php echo Jtext::_('COM_BT_SOCIALCONNECT_SUBMITED_CPANEL') ?>');
									jQuery('.error'+id).removeClass('bt-error');
									jQuery('.error'+id).addClass('bt-submited');
									jQuery('.senttime'+id).text(data.senttime);
									self.addClass('btn-disable');
									self.removeClass('btn-socialsend');
								}
								
							})
						});		
					}
				});				
			return false;
			});
		});				
	});	
</script>