<div id="com-events-categories" class="module_menu_sidebar">
	<h3 class="module-title"><?php echo JText::_('COM_COMMUNITY_CATEGORIES');?></h3>
	<div class="module-ct-inner">
		<div class="app-box-content">
			<ul class="nav">
				<li>
					
					<?php if( $category->parent == COMMUNITY_NO_PARENT && $category->id == COMMUNITY_NO_PARENT ){ ?>
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=events');?>"><?php echo JText::_( 'COM_COMMUNITY_EVENTS_ALL' ); ?> </a>
					<?php }else{ ?>
							<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=display&categoryid=' . $category->parent ); ?>"><?php echo JText::_('COM_COMMUNITY_BACK_TO_PARENT'); ?></a>
					<?php }  ?>
				</li>
				<?php if( $categories ): ?>
					<?php foreach( $categories as $row ): ?>
					<li>
						
						<a href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=display&categoryid=' . $row->id ); ?>">
							<?php echo JText::_( $this->escape($row->name) ); ?><?php if(!empty($row->total)): ?>(<?php echo $row->total; ?>)<?php endif; ?>
						</a>
						
					</li>
					<?php endforeach; ?>
				<?php else: ?>
						<li><?php echo JText::_('COM_COMMUNITY_GROUPS_CATEGORY_NOITEM'); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>
