<div class="cModule cFrontPage-Search quick_search">

	<div class="app-box-content">
		<form name="search" id="cFormSearch" method="get" action="<?php echo CRoute::_('index.php?option=com_community&view=search');?>">

		  <input type="text" class="input-block-level" id="keyword" name="q" />
			<a class="search_form_button" href="javascript:void(0);" onclick="joms.jQuery('#cFormSearch').submit();"><span><?php echo JText::_('COM_COMMUNITY_SEARCH')?></span></a>
			<input type="hidden" name="option" value="com_community" />
			<input type="hidden" name="view" value="search" />
		</form>
	</div>
	<div class="app-box-footer">
		<?php echo JText::sprintf('COM_COMMUNITY_TRY_ADVANCED_SEARCH', CRoute::_('index.php?option=com_community&view=search&task=advancesearch') , JText::_('COM_COMMUNITY_TITLE_CUSTOM_SEARCH') ); ?>
	</div>
</div>