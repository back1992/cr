<?php // no direct access
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

$itemId	= modEasyBlogLatestBloggerHelper::_getMenuItemId($params);

?>

<div id="ezblog-latestblogger" class="ezb-mod mod_easybloglatestblogger<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<?php if($easyblogInstalled): ?>
		<?php if(!empty($bloggers)):
            foreach($bloggers as $blogger) :

            $posterURL      = EasyBlogRouter::_('index.php?option=com_easyblog&view=blogger&layout=listings&id=' . $blogger->id . $itemId );
            $posterLink     = '<a href="'.$posterURL.'">'.$blogger->profile->getName().'</a>';
            $posterWebsite  = '<a href="'.$blogger->profile->getWebsite().'" target="_blank">'.$blogger->profile->getWebsite().'</a>';
        ?>
		<div class="mod-item">

            <div class="mod-author-brief">
            	<?php 
	            //author's avatar
	            if ($params->get('showavatar', true)) : ?>
	                <img class="mod-avatar avatar" src="<?php echo $blogger->profile->getAvatar();?>" width="50" height="50" alt="<?php echo $blogger->profile->getName(); ?>" />
	            <?php endif; ?>

                <?php //author's name & link ?>
                <div class="mod-author-name"><?php echo $posterLink; ?></div>

                <?php //author's total post ?>
                <?php if($params->get('showcount', true)) : ?>
                <div class="mod-author-post small"><?php echo JText::sprintf('MOD_EASYBLOGLATESTBLOGGER_COUNT', $blogger->post_count);?></div>
                <?php endif; ?>
			</div>

            <?php if( $params->get( 'showbio' , true ) ){ ?>
            <div class="mod-author-bio">
                <?php if( $blogger->profile->getBiography() != '' ) : ?>
			        <?php echo (JString::strlen( strip_tags( $blogger->profile->getBiography() ) ) > $params->get( 'bio_length' , 50 ) ) ? JString::substr( strip_tags( $blogger->profile->getBiography() ), 0, $params->get( 'bio_length' , 50 ) ) . '...' : $blogger->profile->getBiography() ; ?>
			    <?php else: ?>
			        "..."
			    <?php endif; ?>
            </div>
            <?php } ?>

            <?php //author's link ?>
            <?php if($params->get('showwebsite', true) && $blogger->profile->getWebsite() != '' && !($blogger->profile->getWebsite() == 'http://')) : ?>
            <div class="mod-author-link small"><?php echo $posterWebsite;?></div>
            <?php endif; ?>
        </div>
    	<?php endforeach; ?>


	<?php else: ?>
		<div class="mod-item-nothing">
			<?php echo JText::_('MOD_EASYBLOGLATESTBLOGGER_NO_BLOGGER'); ?>
		</div>
	<?php endif; ?>
		

	<?php else: ?>
		<div class="mod-item-notinstalled">
			<?php echo JText::_('MOD_EASYBLOGLATESTBLOGGER_EASYBLOG_NOT_INSTALLED'); ?>
		</div>
	<?php endif; ?>
</div>
