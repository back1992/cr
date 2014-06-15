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
<!-- Post item wrapper -->
<div id="entry-<?php echo $row->id; ?>" class="bt-blogger blog-post clearfix prel<?php echo (!empty($row->team)) ? ' teamblog-post' : '' ;?>" itemscope itemtype="http://schema.org/Blog">
	<div class="blog-post-in">

		<!-- @template: Admin tools -->
		<?php echo $this->fetch( 'blog.admin.tool.php' , array( 'row' => $row ) ); ?>
		<?php //var_dump($row); die; ?>
		<!-- Content wrappings -->

		<div class="blog-content clearfix">
			<div class="bt-image-head">
				<?php if( $row->getImage() && $this->getParam( 'blogimage_frontpage') ){ ?>
						<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id='.$row->id); ?>" title="<?php echo $this->escape( $row->title );?>" class="blog-image float-l mrm mbm">
							<img src="<?php echo $row->getImage()->getSource( 'frontpage' );?>" alt="<?php echo $this->escape( $row->title );?>" />
				
							<?php if( $row->isFeatured ) { ?>
								<!-- Show a featured tag if the entry is featured -->
								<div class="tag-featured"><?php echo Jtext::_('COM_EASYBLOG_FEATURED_FEATURED'); ?></div>
							<?php } ?>								
						</a>
				<?php } ?>

			<?php if( 
			$this->getParam( 'show_category' ) ||
			$this->getParam( 'show_author')
			){ ?>				
			<div class="bt-meta-bloger">
				<div class="inner">
				<?php if( $this->getParam( 'show_category' ) ){ ?>
					<!-- Category info -->
					<div class="blog-category">
						<div>
						<?php $categoryName   = isset($row->category) ? JText::_( $row->category ) : $row->getCategoryName(); ?>
						<?php echo JText::sprintf( 'BT_EDUCATION_COM_EASYBLOG_IN' , EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id=' . $row->category_id ), $categoryName ); ?>
						</div>
					</div>
				<?php } ?>
					
				<?php if( $this->getParam( 'show_author') ){ ?>
					<!-- Author information -->
					<div class="blog-author">
						<?php echo JText::_( 'COM_EASYBLOG_BY' );?>
						<a href="<?php echo $row->blogger->getProfileLink(); ?>" itemprop="author"><?php echo $row->blogger->getName(); ?></a>
					</div>
				<?php } ?>			
				</div>
			
			</div>
			<?php } ?>					
			
			</div>
			
			<div class="blog-content-inner">
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
				<h2 id="title-<?php echo $row->id; ?>" class="blog-title<?php echo ($row->isFeatured) ? ' featured' : '';?> rip mbs" itemprop="name">
						<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id='.$row->id); ?>" title="<?php echo $this->escape( $row->title );?>" itemprop="url"><?php echo $row->title; ?></a>
				</h2>
				<div class="clr"></div>
				<!-- Load social buttons -->
				<?php if( in_array( $system->config->get( 'main_socialbutton_position' ) , array( 'top' , 'left' , 'right' ) ) ){ ?>
					<?php echo EasyBlogHelper::showSocialButton( $row , true ); ?>
				<?php } ?>

				<!-- Post content -->
				<div class="blog-text prel">

					<!-- @Trigger: onBeforeDisplayContent -->
					<?php echo $row->event->beforeDisplayContent; ?>

					<!-- Blog Image -->
					

					<!-- Post content -->
					<?php echo $row->text; ?>
						<?php if( $row->readmore ) { ?>
							<div class="bt-blog readmore">
								<span class="icon-readmore">&nbsp;</span>
								<!-- Readmore link -->
								<?php echo $this->fetch( 'blog.readmore.php' , array( 'row' => $row ) ); ?>
							</div>
						<?php } ?>
						
						<!-- Bottom metadata -->
						<div class="blog-meta-bottom">
							<div class="in">

								<?php if( $this->getParam( 'show_hits' , true ) ){ ?>
									<span class="blog-hit"><?php echo JText::sprintf( 'COM_EASYBLOG_HITS_TOTAL' , $row->hits ); ?></span>
								<?php } ?>
								
								<?php echo $this->fetch( 'blog.item.comment.php' , array( 'row' => $row ) ); ?>
								
								<?php if( $system->config->get( 'main_ratings_frontpage' ) ) { ?>
									<!-- Blog ratings -->
									<?php echo $this->fetch( 'blog.rating.php' , array( 'row' => $row , 'locked' => $system->config->get( 'main_ratings_frontpage_locked' ) ) ); ?>
								<?php } ?>

							</div>
						</div>
						<?php if( $this->getParam( 'show_tags' ) && $this->getParam( 'show_tags_frontpage' , true ) ){ ?>
							<?php echo $this->fetch( 'tags.item.php' , array( 'tags' => $row->tags ) );?>
						<?php } ?>							
					<!-- @Trigger: onAfterDisplayContent -->
					<?php echo $row->event->afterDisplayContent; ?>
					
					
					<?php if( $this->getParam( 'show_last_modified' ) ){ ?>
					<!-- Modified date -->
					<span class="blog-modified-date">
						<?php echo JText::_( 'COM_EASYBLOG_LAST_MODIFIED' ); ?>
						<?php echo JText::_( 'COM_EASYBLOG_ON' ); ?>
						<time datetime="<?php echo $this->formatDate( '%Y-%m-%d' , $row->modified ); ?>">
							<span><?php echo $this->formatDate( $system->config->get('layout_dateformat') , $row->modified ); ?></span>
						</time>
					</span>
				<?php } ?>
					
					
					<!-- Copyright text -->
					<?php if( $system->config->get( 'layout_copyrights' ) && !empty($row->copyrights) ) { ?>
						<?php echo $this->fetch( 'blog.copyright.php' , array( 'row' => $row ) ); ?>
					<?php } ?>

					<!-- Maps -->
					<?php if( $system->config->get( 'main_locations_blog_frontpage' ) ){ ?>
						<?php echo EasyBlogHelper::getHelper( 'Maps' )->getHTML( true ,
																				$row->address,
																				$row->latitude ,
																				$row->longitude ,
																				$system->config->get( 'main_locations_blog_map_width') ,
																				$system->config->get( 'main_locations_blog_map_height' ),
																				JText::sprintf( 'COM_EASYBLOG_LOCATIONS_BLOG_POSTED_FROM' , $row->address ),
																				'post_map_canvas_' . $row->id );?>
					<?php } ?>
				</div>


				


				<!-- Load bottom social buttons -->
				<?php if( $system->config->get( 'main_socialbutton_position' ) == 'bottom' ){ ?>
					<?php echo EasyBlogHelper::showSocialButton( $row , true ); ?>
				<?php } ?>

				<!-- Standard facebook like button needs to be at the bottom -->
				<?php if( $system->config->get('main_facebook_like') && $system->config->get('main_facebook_like_layout') == 'standard' && $system->config->get( 'integrations_facebook_show_in_listing') ) : ?>
					<?php echo $this->fetch( 'facebook.standard.php' , array( 'facebook' => $row->facebookLike ) ); ?>
				<?php endif; ?>

				<?php if( $system->config->get( 'layout_showcomment' ) && EasyBlogHelper::getHelper( 'Comment')->isBuiltin() ){ ?>
					<!-- Recent comment listings on the frontpage -->
					<?php echo $this->fetch( 'blog.item.comment.list.php' , array( 'row' => $row ) ); ?>
				<?php } ?>
				
			</div>
		</div>
	</div>
</div>
