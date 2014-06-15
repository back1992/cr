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
<?php if( $this->getParam( 'show_author') || $this->getParam( 'show_created_date') || $this->getParam( 'show_category') ) { ?>
<div class="blog-meta-list-item<?php echo !empty( $row->source ) ? ' micro ' . $row->source : ''; ?>">

		<!--<span><?php echo $postedText; ?></span>-->
		
		<?php if( $this->getParam( 'show_author') ){ ?>
			<!-- Author information -->
			<div class="blog-author">
				<?php // echo JText::_( 'COM_EASYBLOG_BY' );?>
				<a href="<?php echo $row->blogger->getProfileLink(); ?>" itemprop="author"><?php echo $row->blogger->getName(); ?></a>
			</div>
		<?php } ?>

		<?php if( $this->getParam( 'show_created_date' ) ){ ?>
			<!-- Creation date -->
			<div class="blog-created">
				<?php // echo JText::_( 'COM_EASYBLOG_ON' ); ?>
				
					<?php echo $this->formatDate('d F Y' , $row->{$this->getParam( 'creation_source')} ); ?>
				
			</div>
		<?php } ?>

		<?php if( $this->getParam( 'show_category' ) ){ ?>
			<!-- Category info -->
			<div class="blog-category">
				<?php $categoryName   = isset($row->category) ? JText::_( $row->category ) : $row->getCategoryName(); ?>
				<?php echo JText::sprintf( 'BT_EDUCATION_COM_EASYBLOG_IN' , EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id=' . $row->category_id ), $categoryName ); ?>
			</div>
		<?php } ?>

</div>
<?php } ?>