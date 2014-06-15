<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- NAV HELPER -->
<nav class="t3-navhelper<?php $this->_c('navhelper') ?>">
  <div class="container">
    <jdoc:include type="modules" name="<?php $this->_p('navhelper') ?>" />
	<div class="back_to_top"><a href="#top" title="<?php echo JText::_("BT_EDUCATION_BACK_TO_TOP") ?>"><span><?php echo JText::_('BT_EDUCATION_BACK_TO_TOP') ?></span><i class="icon-double-angle-up icon-2x icon-fixed-width"></i></a></div>
  </div>
  <script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('a[href=#top]').click(function(){
		    jQuery('html, body').animate({scrollTop:0}, 'slow');
		    return false;
		 });	
	});
	
	
</script>
</nav>
<!-- //NAV HELPER -->