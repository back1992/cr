<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Mainbody 3 columns, content in left, mast-col on top of 2 sidebars: content - sidebar1 - sidebar2
 */
defined('_JEXEC') or die;
$document=  JFactory::getDocument();
$option = JRequest::getVar('option');
?>

<?php

  // Layout configuration
  $layout_config = json_decode ('{  
    "two_sidebars": {
      "default" : [ "span6"         , "span6"             , "span3"               , "span3"           ],
      "wide"    : [],
      "xtablet" : [ "span8"         , "span4"             , "span4"               , "span4 spanfirst"           ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  , "span6"               , "span6"           ]
    },
    "one_sidebar": {
      "default" : [ "span9"         , "span3"             , "span3"             ],
      "wide"    : [],
      "xtablet" : [ "span8"         , "span4"             , "span4"             ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  , "span12"            ]
    },
    "no_sidebar": {
      "default" : [ "span12" ]
    }
  }');

  // positions configuration
  $mastcol  = 'mast-col';
  $sidebar1 = 'sidebar-1';
  $sidebar2 = 'sidebar-2';

  // Detect layout
  if (($option =='com_kunena')){
	$layout = "no_sidebar";
  }
  elseif ($this->countModules($mastcol) or $this->countModules("$sidebar1 and $sidebar2")) {
    $layout = "two_sidebars";
  } elseif ($this->countModules("$sidebar1 or $sidebar2")) {
    $layout = "one_sidebar";
  } else {
    $layout = "no_sidebar";
  }

  $layout = $layout_config->$layout;

  $col = 0;
?>
<!-- MOD CONTACT MAP -->
<?php if ($this->countModules('contact-map')) : ?>
	<div id="bt-contact-map" class="bt-contact-map container">
		<jdoc:include type="modules" name="<?php $this->_p('contact-map') ?>" style="xhtml" />
	</div>
<?php endif; ?>	
<div id="t3-mainbody" class="container t3-mainbody">
  <div class="row">
    
    <!-- MAIN CONTENT -->
    <div id="t3-content" class="t3-content <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
      <jdoc:include type="message" />
      <jdoc:include type="component" />
    </div>
    <!-- //MAIN CONTENT -->
    <?php  if (($option !='com_kunena')): ?>
		<?php if ($this->countModules("$sidebar1 or $sidebar2 or $mastcol")) : ?>
		<div class="t3-sidebar <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
		  <?php if ($this->countModules($mastcol)) : ?>
		  <!-- MASSCOL 1 -->
		  <div class="t3-mastcol t3-mastcol-1<?php $this->_c($mastcol)?>">
			<jdoc:include type="modules" name="<?php $this->_p($mastcol) ?>" style="T3Xhtml" />
		  </div>
		  <!-- //MASSCOL 1 -->
		  <?php endif ?>

		  <?php if ($this->countModules("$sidebar1 or $sidebar2")) : ?>
		  <div class="row">
			<?php if ($this->countModules($sidebar1)) : ?>
			<!-- SIDEBAR 1 -->
			<div class="t3-sidebar t3-sidebar-1 <?php echo $this->getClass($layout, $col) ?><?php $this->_c($sidebar1)?>" <?php echo $this->getData ($layout, $col++) ?>>
			  <jdoc:include type="modules" name="<?php $this->_p($sidebar1) ?>" style="T3Xhtml" />
			</div>
			<!-- //SIDEBAR 1 -->
			<?php endif ?>
			
			<?php if ($this->countModules($sidebar2)) : ?>
			<!-- SIDEBAR 2 -->
			<div class="t3-sidebar t3-sidebar-2 <?php echo $this->getClass($layout, $col) ?><?php $this->_c($sidebar2)?>" <?php echo $this->getData ($layout, $col++) ?>>
			  <jdoc:include type="modules" name="<?php $this->_p($sidebar2) ?>" style="T3Xhtml" />
			</div>
			<!-- //SIDEBAR 2 -->
			<?php endif ?>
		  </div>
		  <?php endif ?>
		</div>
		<?php endif ?>
	<?php endif ?>
	
  </div>
</div> 


<?php  if (($option =='com_community')): ?>
<script type="text/javascript">
screenWidth = jQuery(document).width();

if (screenWidth < 767){
	jQuery(document).ready(function(){
		jQuery('#t3-mainnav .navbar .btn-navbar').click(function(){
			jQuery('#t3-mainnav #nav-collapse').slideToggle(400, function(){
					jQuery('#t3-mainnav #nav-collapse').css({'height':'auto'});
			});
					
		});	

		
	});	
}
</script>
<style>
@media (max-width: 767px) {
	#t3-mainnav #nav-collapse.collapse{transition: none 0s ease 0s;}
}
</style>
<?php endif ?>











