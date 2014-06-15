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
?>
<form action="index.php" method="post" name="adminForm" class="form-validate <?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'isJ30' : 'isJ25') ?>">
	<ul class="nav nav-tabs">
			<li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('COM_BT_SOCIALCONNECT_USERS_TATISTIC'); ?></a></li>
			<li><a href="#options" data-toggle="tab"><?php echo JText::_('COM_BT_SOCIALCONNECT_SYSTEMS_TATISTIC'); ?></a></li>
	</ul>
	<div class="tab-content">	
		<div class="tab-pane active" id="details">
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="StatisticPage" class="table">
			<tr>
			<td>			
				<fieldset class="adminform">
					
					 <table class="adminlist table table-striped">	
					 <thead>
						<tr>
						  <th width="300"></th>
						  <td><strong><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_CONNECTION_NUMBER'); ?></strong></td>
						</tr>
					  </thead>					  
						  <tbody>
							<tr>
							  <th width="300"><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_NUMBER'); ?></th>
							  <td><?php echo $this->numb; ?></td>
							</tr>
						</tbody>
					</table>
					
					 <table class="adminlist table table-striped">
					  <thead>
						<tr>
						  <th width="300"><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_CONNECTION'); ?></th>
						  <td><strong><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_CONNECTION_NUMBER'); ?></strong></td>
						</tr>
					  </thead>
					  <tfoot>
						<tr>
						  <th colspan="2">&nbsp;</th>
						</tr>
					  </tfoot>
					  <tbody>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_FACEBOOK'); ?></th>
						  <td><?php echo $this->model->getStatistic('facebook'); ?></td>
						</tr>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_TWITTER'); ?></th>
						  <td><?php echo $this->model->getStatistic('twitter'); ?></td>
						</tr>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_GOOGLE'); ?></th>
						  <td><?php echo $this->model->getStatistic('google'); ?></td>
						</tr>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_LINKEDIN'); ?></th>
						  <td><?php echo $this->model->getStatistic('linkedin'); ?></td>
						</tr>
					</tbody>
					</table>
					<table class="adminlist table table-striped">
					  <thead>
						<tr>
						  <th width="300"><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_MESSAGE_POST'); ?></th>
						  <td><strong><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_CONNECTION_NUMBER'); ?></strong></td>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_COUNT_POST_DESC'); ?></th>
						</tr>
					  </thead>
					  <tfoot>
						<tr>
						  <th colspan="3">&nbsp;</th>
						</tr>
					  </tfoot>
					  <tbody>
						<tr>
						  <th width="300"><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_FACEBOOK'); ?></th>
						  <td><?php echo $this->model->getPostmessage('facebook'); ?></td>
						  <td><?php echo (int) $this->model->getCountclick('facebook'); ?></td>
						</tr>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_TWITTER'); ?></th>
						  <td><?php echo $this->model->getPostmessage('twitter'); ?></td>
						  <td><?php echo (int)$this->model->getCountclick('twitter'); ?></td>
						</tr>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_GOOGLE'); ?></th>
						  <td><?php echo $this->model->getPostmessage('google'); ?></td>
						  <td><?php echo (int)$this->model->getCountclick('google'); ?></td>
						</tr>
						<tr>
						  <th><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_LINKEDIN'); ?></th>
						  <td><?php echo $this->model->getPostmessage('linkedin'); ?></td>
						  <td><?php echo (int)$this->model->getCountclick('linkedin'); ?></td>
						</tr>
					</tbody>
					</table>
				</fieldset>
			
			</td>
			</tr>
			</table>
		</div>
		<div class="tab-pane" id="options">
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="k2InfoPage" class="table">
			<tr>
			<td>			
			  <fieldset class="adminform">
					
					<table class="adminlist table table-striped">	
					<thead>
						<tr>
						  <th width="300"></th>
						  <td><strong><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_USER_CONNECTION_NUMBER'); ?></strong></td>
						</tr>
					  </thead>					 
						  <tbody>
							<tr>
							  <th width="300"><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_MESSAGE_ERROR'); ?></th>
							  <td><?php echo $this->model->getMesageerror(); ?></td>
							</tr>
						</tbody>
						
					</table>
					<table class="adminlist table table-striped">						  
						  <tbody>
							<tr>
							  <td width="300"><strong><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_WIDGET_INUSE'); ?></strong></td>
							  <td><?php echo $this->model->getNumberSocial('widgets'); ?></td>
							</tr>
						</tbody>
					</table>
					<table class="adminlist table table-striped">						  
						  <tbody>
							<tr>
							  <th width="300"><?php echo JText::_('COM_BT_SOCIALCONNECT_STATISTIC_PUBLISHING_INUSE'); ?></th>
							  <td><?php echo $this->model->getNumberSocial('publishes'); ?></td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			
			</td>
			</tr>
			</table>
		</div>
	</div>
</form>