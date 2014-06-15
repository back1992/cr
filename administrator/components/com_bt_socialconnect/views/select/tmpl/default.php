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
 
// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
?>


 <fieldset>
   <legend><h3 class="modal-title"><?php echo JText::_('COM_BT_SOCIALCONNECT_TYPE_CHOOSE')?></h3></legend>
<ul id="new-widget-list">
<?php foreach ($this->Items as &$item) : ?>
	<li style="float:left;<?php echo (!Bt_SocialconnectLegacyHelper::isLegacy() ? 'width:50%' : 'width:30%');?>">
		<?php
		// Prepare variables for the link.
		$name	= $this->escape($item->title);	
		$link	= 'index.php?option=com_bt_socialconnect&task=widget.add&wgtype='.$item->type.'&title='.$name;		
		$desc	= $this->escape($item->desc);
		?>
		<span class="editlinktip hasTip" title="<?php echo $name.' :: '.$desc; ?>">
			<a href="<?php echo JRoute::_($link);?>" target="_top">
				<?php echo $name; ?></a></span>
	</li>
<?php endforeach; ?>

</ul>
</fieldset>
<div class="clr"></div>
