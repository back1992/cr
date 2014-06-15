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
?>

<div class="profile">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<?php  echo $this->loadTemplate('info'); ?>
<?php if($this->active!=''):
	JError::raiseWarning('', JTEXT::_('COM_BT_SOCIALCONNECT_CONNECT_ACTIVE'));
endif;?>
</div>
