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
<div>
    <div class="app-box-content">
        <ul class="cThumbDetails cResetList">
            <?php foreach ($events as $event) { ?>
                <li <?php if (!empty($event->summary)): ?>class="jomNameTips" title="<?php echo CStringHelper::escape($event->summary); ?>" <?php endif; ?>>
                    <div class="cThumb-Calendar cFloat-L">
						<div class="cThumb-event-month"><?php echo CEventHelper::formatStartDate($event, JText::_('M')); ?></div>
                        <div class="cThumb-event-day"><?php echo CEventHelper::formatStartDate($event, JText::_('d')); ?></div>
                    </div>
                    <div class="cThumb-Detail">
                        <a href="<?php echo $event->getLink(); ?>" class="cThumb-Title"><?php echo CStringHelper::escape($event->title); ?></a>
                        <div class="cThumb-Location">
                            <?php echo CStringHelper::escape($event->location); ?>
                        </div>
                        <div class="cThumb-Members small">
                            <a href="<?php echo $event->getGuestLink(COMMUNITY_EVENT_STATUS_ATTEND); ?>">
                                <?php echo JText::sprintf((!CStringHelper::isSingular($event->confirmedcount)) ? 'COM_COMMUNITY_EVENTS_ATTANDEE_COUNT_MANY' : 'COM_COMMUNITY_EVENTS_ATTANDEE_COUNT', $event->confirmedcount); ?>
                            </a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="app-box-footer">
        <a href="<?php echo CRoute::_('index.php?option=com_community&view=events'); ?>"><?php echo JText::_('COM_COMMUNITY_FRONTPAGE_VIEW_ALL_EVENTS'); ?></a>
    </div>
</div>