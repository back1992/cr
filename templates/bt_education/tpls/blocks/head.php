<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<!-- META FOR IOS & HANDHELD -->
<?php if($this->getParam('responsive', 1)): ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<?php endif ?>
<meta name="HandheldFriendly" content="true" />
<meta name="apple-mobile-web-app-capable" content="YES" />
<!-- //META FOR IOS & HANDHELD -->

<?php 
// SYSTEM CSS
$this->addStyleSheet(JURI::base(true).'/templates/system/css/system.css'); 
?>

<?php 
// T3 BASE HEAD
$this->addHead(); ?>

<link href="<?php echo T3_TEMPLATE_URL ?>/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css' />
<!--<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>-->
<link href='http://fonts.googleapis.com/css?family=Merriweather:300normal,300italic,400normal,400italic,700normal,700italic,900normal,900italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href="<?php echo T3_TEMPLATE_URL ?>/css/kiennb.css" rel="stylesheet" />

<link rel="stylesheet" href="<?php echo T3_TEMPLATE_URL ?>/css/css3_effect.css" type="text/css" />
<script type="text/javascript" src="<?php echo T3_TEMPLATE_URL ?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo T3_TEMPLATE_URL ?>/js/script.js"></script>



<?php if(is_file(T3_TEMPLATE_PATH . '/css/custom.css')) { ?>
	<link href="<?php echo T3_TEMPLATE_URL ?>/css/custom.css" rel="stylesheet" />
<?php } ?>

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- For IE6-8 support of media query -->
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo T3_URL ?>/js/respond.min.js"></script>
<![endif]-->

<!-- You can add Google Analytics here-->