<?php 
$app = JFactory::getApplication();
$menu = $app->getMenu();
$lang = JFactory::getLanguage();



?>

<?php if ($menu->getActive() == $menu->getDefault($lang->getTag()) ) : ?>

		<?php if ($this->countModules('background_slideshow')) : ?>
			<div id="background_slideshow" class="background_slideshow">
				
					
						<jdoc:include type="modules" name="background_slideshow" style="raw" />
					
				
			</div>    
		<?php endif; ?>

<?php else : ?>		
	
		<?php if ($this->countModules('background_slideshow_content')) : ?>
			<div id="background_slideshow" class="background_slideshow_content">
				<jdoc:include type="modules" name="<?php $this->_p('background_slideshow_content') ?>" style="raw" />
			</div>
		<?php endif; ?>	
		
<?php endif; ?>		



		
		
		
		
		
		
		
		
		
		
		
		