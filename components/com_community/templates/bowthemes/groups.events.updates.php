<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
?>
<?php if( $events ){ ?>
<div class="jomsocial-module cGroups-EventsUpdates app-box">
	<h3 class="module-title">
		<?php echo JText::_('COM_COMMUNITY_EVENTS_UPCOMING'); ?>
	</h3>
	<div class="module-ct-inner">
	<div class="app-box-content">
		<ul class="cThumbDetails cResetList">
			<?php foreach($events as $event){ ?>
			<li>
				<div class="cThumb-Calendar cFloat-L">
					<div class="cThumb-event-month"><?php echo CEventHelper::formatStartDate($event, JText::_('M') ); ?></div>
					<div class="cThumb-event-day"><?php echo CEventHelper::formatStartDate($event, JText::_('d') ); ?></div>
				</div>
				<div class="cThumb-Detail">
					<a class="cThumb-Title" href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewevent&eventid='.$event->id); ?>"><?php echo $this->escape($event->title); ?></a>
					<div class="cThumb-Location">
						<?php echo $this->escape( $event->location );?>
					</div>
					<div class="cThumb-Members small">
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=viewguest&eventid=' . $event->id . '&type='.COMMUNITY_EVENT_STATUS_ATTEND);?>">
							<?php echo JText::sprintf((CStringHelper::isPlural($event->confirmedcount)) ? 'COM_COMMUNITY_EVENTS_MANY_GUEST_COUNT':'COM_COMMUNITY_EVENTS_GUEST_COUNT', $event->confirmedcount );?>
						</a>
					</div>
				</div>
			</li>		 
			<?php } ?>
		</ul>
	</div>
	</div>
</div>
<?php } ?>
