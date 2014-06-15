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
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHtml::_('behavior.keepalive');
?>
<div id="mod-bt-socialconnect-<?php echo $module->id?>" class="<?php echo $moduleclass_sfx; ?>">
	<?php if($params->get('label','') !=''): ?>
		<div class="pretext">
			<p><?php echo $params->get('label',''); ?></p>
		</div>
	<?php endif; ?>
	<?php echo $item; ?>
</div>
