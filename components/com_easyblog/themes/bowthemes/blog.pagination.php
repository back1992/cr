<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');
?>
<ul class="list-pagination reset-ul float-li clearfull">

	<li class="start<?php if( !$data->start->link ){ echo " disble"; }?>">
		<a href="<?php echo EasyBlogHelper::uniqueLinkSegments( $data->start->link ); ?>" rel="start"><?php echo JText::_( 'BT_COM_EASYBLOG_PAGINATION_START' );?></a>
	</li>


	<li class="older<?php if( !$data->previous->link ){ echo " disble"; }?>">
	<a href="<?php echo EasyBlogHelper::uniqueLinkSegments( $data->previous->link ); ?>" rel="prev"><?php echo JText::_( 'COM_EASYBLOG_PAGINATION_PREVIOUS' );?></a>
	</li>


	<?php foreach( $data->pages as $page ){ ?>
		<?php 	if( $page->link ) { ?>
		<li>
			<a href="<?php echo EasyBlogHelper::uniqueLinkSegments( $page->link ); ?>"><?php echo $page->text;?></a>
		</li>
		<?php 	} else { ?>
		<li class="active"><b><?php echo $page->text;?></b></li>
		<?php 	} ?>
	<?php } ?>


	<li class="newer<?php if( !$data->next->link ){ echo " disble"; }?>"><a href="<?php echo EasyBlogHelper::uniqueLinkSegments( $data->next->link ); ?>" rel="next"><?php echo JText::_( 'COM_EASYBLOG_PAGINATION_NEXT' ); ?></a></li>


	<li class="end<?php if( !$data->end->link ){ echo " disble"; }?>"><a href="<?php echo EasyBlogHelper::uniqueLinkSegments( $data->end->link ); ?>" rel="end"><?php echo JText::_( 'BT_COM_EASYBLOG_PAGINATION_END' ); ?></a></li>

</ul>
