<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$sitename  = $this->params->get('sitename') ? $this->params->get('sitename') : JFactory::getConfig()->get('sitename');
$slogan    = $this->params->get('slogan');
$logotype  = $this->params->get('logotype', 'text');
//$logoimage = $logotype == 'image' ? $this->params->get('logoimage', 'templates/' . T3_TEMPLATE . '/images/logo.png') : '';
//$logoimgsm = ($logotype == 'image' && $this->params->get('enable_logoimage_sm', 0)) ? $this->params->get('logoimage_sm', '') : false;
//if ($logoimgsm) {
 // $logoimgsm = ' style="background-image:url('.JURI::base(true).'/'.$logoimage.');"';
//}
$logoimage = $logotype == 'image' ? $this->params->get('logoimage', '') : '';
if ($logoimage) {
	$logoimage = ' style="background-image:url('.JURI::base(true).'/'.$logoimage.');"';
} else {
	$logoimage = ' style="background-image:url('.JURI::base(true).'/templates/'.T3_TEMPLATE.'/images/logo.png);"';
}
?>

<!-- HEADER -->
<header id="t3-header" class="t3-header">



<div class="header_top">
<div class="header_top_inner">
	<div class="header_top_inner_left"></div>
	<div class="header_top_inner_right"></div>
</div>
	<div class="container">
		
		
		<?php if ($this->countModules('head_infor or head_login or head_search')) : ?>
			<div class="top_header_right">  
				<?php if ($this->countModules('head_infor')) : ?>
					<div class="head_infor<?php $this->_c('head_infor')?>">     
						<jdoc:include type="modules" name="<?php $this->_p('head_infor') ?>" style="raw" />
					</div>
				<?php endif ?>
				
				<?php if ($this->countModules('head_login')) : ?>
					<div class="head_login<?php $this->_c('head_login')?>">     
						<jdoc:include type="modules" name="<?php $this->_p('head_login') ?>" style="raw" />
					</div>
				<?php endif ?>
				
				<?php if ($this->countModules('head_search')) : ?>
					<div class="head_search<?php $this->_c('head_search')?>">     
						<jdoc:include type="modules" name="<?php $this->_p('head_search') ?>" style="raw" />
					</div>
				<?php endif ?>
			<div style="clear:both;"></div>
			</div>
			
		<?php endif ?>
			
		<?php if($this->countModules('social_header')): ?>
			<div class="top_header_left">  
				<?php if ($this->countModules('social_header')) : ?>
					<div class="social_header<?php $this->_c('social_header')?>">     
						<jdoc:include type="modules" name="<?php $this->_p('social_header') ?>" style="raw" />
					</div>
				<?php endif ?>
		  
			</div>
		<?php endif ?>
		
	
	<div style="clear:both;"></div>
	</div>
</div>


<div class="header_bottom">
<div class="header_bottom_inner">
	<div class="header_bottom_inner_left"></div>
	<div class="header_bottom_inner_right"></div>
</div>
  <div class="container">
    <!-- LOGO -->
    <div class="logo">
      <div class="logo-<?php echo $logotype ?>">
        <a href="<?php echo JURI::base(true) ?>" title="<?php echo strip_tags($sitename) ?>" <?php echo $logoimage ?>>
          <span><?php echo $sitename ?></span>
        </a>
        <small class="site-slogan hidden-phone"><?php echo $slogan ?></small>
      </div>
    </div>
    <!-- //LOGO -->
	
	<nav id="t3-mainnav" class="t3-mainnav navbar-collapse-fixed-top">
		<div class="navbar">
			<div class="navbar-inner">

			  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#nav-collapse">
				<i class="icon-reorder"></i>
			  </button>

			  <div id="nav-collapse" class=" nav-collapse collapse<?php echo $this->getParam('navigation_collapse_showsub', 1) ? ' always-show' : '' ?>">
			  <?php if ($this->getParam('navigation_type') == 'megamenu') : ?>
				<jdoc:include type="megamenu" />
			  <?php else : ?>
				<jdoc:include type="modules" name="<?php $this->_p('mainnav') ?>" style="raw" />
			  <?php endif ?>
			  </div>
			</div>
		</div>
	</nav>

    

  </div>
</div>
</header>
<!-- //HEADER -->
