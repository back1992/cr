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

// $extraClass     = 'blog-meta';

// Altium
// If view is entry, use 'fsm'
// If view is latest or other, use mbm
// if type is micro use micro , else twitter or email

// Default
// If view is not entry use 'mbm'
// If view is not entry and microtype use class 'micro'
// If twitter use 'twitter'
?>
<?php if( $this->getParam( 'show_author') || $this->getParam( 'show_created_date') || $this->getParam( 'show_category') ) { ?>
<div class="blog-meta-list-item<?php echo !empty( $row->source ) ? ' micro ' . $row->source : ''; ?>">
			
		

		<?php if( $this->getParam( 'show_created_date' ) ){ ?>
			<!-- Creation date -->
			<!--<div class="blog-created">
				<?php // echo JText::_( 'COM_EASYBLOG_ON' ); ?>
				
					<?php echo $this->formatDate('d F Y' , $row->{$this->getParam( 'creation_source')} ); ?>
				
			</div>-->
		<?php } ?>
		
		<?php if( $this->getParam( 'show_created_date' ) ){ ?>
					<!-- Creation date -->
					<div class="blog-created">
						<?php // echo JText::_( 'COM_EASYBLOG_ON' ); ?>
							<span class="date">
								<?php echo $this->formatDate('d' , $row->{$this->getParam( 'creation_source')} ); ?>
							</span> <br />
							<span class="month">
								<?php echo $this->formatDate('F' , $row->{$this->getParam( 'creation_source')} ); ?>
							</span> 
						
					</div>
				<?php } ?>	
		
		<?php if( $this->getParam( 'show_author') ){ ?>
			
			<!-- Author information -->
			<div class="blog-author">
				<?php echo JText::_( 'COM_EASYBLOG_BY' );?>
				<a href="<?php echo $row->blogger->getProfileLink(); ?>" itemprop="author"><?php echo $row->blogger->getName(); ?></a>
			</div>
		<?php } ?>
		
		<?php if( $this->getParam( 'show_category' ) ){ ?>
			<!-- Category info -->
			<div class="blog-category">
				<?php $categoryName   = isset($row->category) ? $row->category : $row->getCategoryName(); ?>
				<?php echo JText::sprintf( 'COM_EASYBLOG_IN' , EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id=' . $row->category_id ), $categoryName ); ?>
			</div>
		<?php } ?>
	
</div>
<?php } ?>