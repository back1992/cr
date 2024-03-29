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

<div class="jomsocial-module cProfile-Friends app-box">
	<h3 class="module-title"><?php echo JText::_('COM_COMMUNITY_PROFILE_FRIENDS'); ?></h3>
	<div class="module-ct-inner">
	<div class="app-box-content">
		<?php
		if( $friends )
		{
		?>
		<ul class="cResetList cThumbsList clearfix">
			<?php
			for($i = 0; ($i < 12) && ($i < count($friends)); $i++) {
				$friend =& $friends[$i];
			?>
			<li>
				<a href="<?php echo CRoute::_('index.php?option=com_community&view=profile&userid='.$friend->id); ?>">
					<img alt="<?php echo $friend->getDisplayName();?>" title="<?php echo $friend->getTooltip(); ?>" src="<?php echo $friend->getThumbAvatar(); ?>" class="cAvatar jomNameTips" />
				</a>
			</li>
			<?php } ?>
		</ul>
		<?php
		}
		else
		{
		?>
			<div class="cEmpty"><?php echo JText::_('COM_COMMUNITY_NO_FRIENDS_YET');?></div>
		<?php
		}
		?>
	</div>
	<div class="app-box-footer">
		<a href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid=' . $user->id ); ?>">
			<span><?php echo JText::_('COM_COMMUNITY_FRIENDS_VIEW_ALL'); ?></span>
			<span>(<?php echo $total;?>)</span>
		</a>
	</div>
</div>
</div>
