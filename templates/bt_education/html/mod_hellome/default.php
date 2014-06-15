<?php
/**
 * @category	Module
 * @package		JomSocial
 * @subpackage	HelloMe
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access'); ?>
<div class="bg-cHello-Me-left">
  <div class="bg-cHello-Me-right">
    <div id="cModule-HelloMe" class="cMods cMods-HelloMe<?php echo $params->get('moduleclass_sfx'); ?>">
      
      <?php

if ($my->isOnline() && $my->id != 0)
{
	$inboxModel  = CFactory::getModel('inbox');
	$notifModel  = CFactory::getModel('notification');
	$friendModel = CFactory::getModel('friends');
	$profileid   = JRequest::getVar('userid', 0, 'GET');
	$filter      = array(
		'user_id' => $my->id
	);
	
	$toolbar              = CToolbarLibrary::getInstance();
	$newMessageCount      = $toolbar->getTotalNotifications( 'inbox' );
	$newEventInviteCount  = $toolbar->getTotalNotifications( 'events' );
	$newFriendInviteCount = $toolbar->getTotalNotifications( 'friends' );
	$newGroupInviteCount  = $toolbar->getTotalNotifications( 'groups' );
	
	$myParams            = $my->getParams();
	$newNotificationCount = $notifModel->getNotificationCount($my->id,'0',$myParams->get('lastnotificationlist',''));
	$newEventInviteCount  = $newEventInviteCount + $newNotificationCount;
	
	$params->def('unreadCount',	$inboxModel->countUnRead ( $filter ));
	$params->def('pending', $friendModel->countPending( $my->id ));
	$params->def('myLink', CRoute::_('index.php?option=com_community&view=profile&userid='.$my->id));
	$params->def('myName', $my->getDisplayName());
	$params->def('myAvatar', $my->getAvatar());
	$params->def('myId', $my->id);
	$CUserPoints = new CUserPoints();
	$params->def('myKarma',$CUserPoints->getPointsImage($my));
	$params->def('enablephotos', $config->get('enablephotos'));
	$params->def('enablevideos', $config->get('enablevideos'));
	$params->def('enablegroups', $config->get('enablegroups'));
	$params->def('enableevents', $config->get('enableevents'));
	
	$enablekarma = $config->get('enablekarma') ? $params->get('show_karma', 1) : $config->get('enablekarma');
	$params->def('enablekarma', $enablekarma);
	
	$modHelloMeHelper = new modHelloMeHelper();
	$COwnerHelper = new COwnerHelper();
	$js       = $modHelloMeHelper->getHelloMeScript( $my->getStatus() , $COwnerHelper->isMine($my->id, $profileid) );
	$document = JFactory::getDocument();
	$document->addScriptDeclaration($js);
	
	if ($params->get('enable_facebookconnect', '1'))
	{
		$params->def('facebookuser', $modHelloMeHelper->isFacebookUser());
	}
	else
	{
		$params->def('facebookuser', false);
	}
	
	$unreadCount  = $params->get('unreadCount', 1);
	$pending      = $params->get('pending', 1);
	$myLink       = $params->get('myLink', 1);
	$myName       = $params->get('myName', 1);
	$myAvatar     = $params->get('myAvatar', 1);
	$myId         = $params->get('myId', 1);
	$myKarma      = $params->get('myKarma', 1);
	$enablephotos = $params->get('enablephotos', 1);
	$enablevideos = $params->get('enablevideos', 1);
	$enablegroups = $params->get('enablegroups', 1);
	$enableevents = $params->get('enableevents', 1);
	$show_avatar  = $params->get('show_avatar', 1);
	$show_karma   = $params->get('enablekarma', 1);
	$show_myblog  = $params->get('show_myblog', 1);
	$facebookuser = $params->get('facebookuser', false);
	$config       = CFactory::getConfig();
	$uri          = CRoute::_('index.php?option=com_community' , false );
	$uri          = base64_encode($uri);
?>
      
      <div class="cHello-Header">
		
		<?php if ( $params->get('enable_alert',1) == 1 ) { ?>
                <div class="cMod-Notify">
                    <a class="hellome-icon-notification" href="javascript:joms.notifications.showWindow();"  title="<?php echo JText::_('COM_COMMUNITY_NOTIFICATIONS_GLOBAL'); ?>">
                       
                        <span class="notifcount"><?php echo ($newEventInviteCount) ? $newEventInviteCount : 0 ;?></span>
                    </a>
                    <a  class="hellome-icon-friend" href="<?php echo CRoute::_('index.php?option=com_community&view=friends&task=pending'); ?>" onclick="joms.notifications.showRequest();return false;"  title="<?php echo JText::_('COM_COMMUNITY_NOTIFICATIONS_INVITE_FRIENDS'); ?>">
                        
                        <span class="notifcount"><?php echo ($newFriendInviteCount) ? $newFriendInviteCount : 0 ;?></span>

                    </a>
                    <a class="hellome-icon-inbox" href="<?php echo CRoute::_('index.php?option=com_community&view=inbox'); ?>"   onclick="joms.notifications.showInbox();return false;" title="<?php echo JText::_('COM_COMMUNITY_NOTIFICATIONS_INBOX'); ?>">
                        
                        <span class="notifcount"><?php echo ($newMessageCount) ? $newMessageCount : 0 ;?></span>
                    </a>
                </div>
            <?php } ?>
		
        <div class="link-user-1">
          <div class="link-user-2">
            <div class="link-user-3"><a class="hello-me-name link-user-jomsocial" href="<?php echo $myLink; ?>" ><?php echo $myName; ?></a>
              
              <?php if ($show_avatar) { ?>
              <a href="<?php echo $myLink; ?>" class="hello-me-name-img link-user-jomsocial"><img class="cHello-Avatar" src="<?php echo $myAvatar; ?>" alt="<?php echo CStringHelper::escape($myName); ?>" /></a>
              <?php } ?>
            </div>
          </div>
          </div>
        <div class="cHello-Karma">
          <?php if ($show_karma) { ?>
          <img src="<?php echo $myKarma; ?>" alt="<?php echo JText::_('MOD_HELLOME_KARMA'); ?>" width="103" height="19" style="margin: 5px 0 0;" />
          <?php } ?>
        </div>
        
        
        
        
        <span id="hellomeLoading" style="display:none"><img src="<?php echo JURI::base().'/components/com_community/templates/default/images/mini-loader.gif' ?>" alt="Loading..."/></span>
        
        <div class="cHello-Status helloMeStatusText">
          <div id="helloMeEdit" style="display: none;">
            <input name="helloMeStatusText" id="helloMeStatusText" type="text" value="" onblur="helloMe.saveStatus();return false;" onkeyup="helloMe.saveChanges(event);return false;" />
            </div>
          <div id="helloMeDisplay">
            <span href="javascript:void(0);" id="helloMeStatusLink" style="text-decoration: none; cursor: pointer;" onclick="helloMe.changeStatus();">
              <span id="helloMeStatus" style="text-decoration: none;"></span>
            </span>
            </div>
          
          <a class="hellome-icon save-stt" href="javascript:void(0);" id="saveLink" style="display: none;" onclick="helloMe.saveStatus();"><?php echo JText::_('MOD_HELLOME_SAVE_MY_STATUS'); ?></a>
          <a class="hellome-icon edit-stt" href="javascript:void(0);" id="editLink" style="display: block;" onclick="helloMe.changeStatus();"><?php echo JText::_('MOD_HELLOME_EDIT_MY_STATUS'); ?></a>
          </div>
        
        </div><!--.cHello-Header-->
      
      
      
      <div class="cHello-Menu">
        <div class="hellome-icon hellome-friend">
          <!--<i class="com-icon-user"></i>-->
          
          <a  href="<?php echo CRoute::_('index.php?option=com_community&view=friends&userid='.$myId); ?>"><?php echo JText::_('MOD_HELLOME_MY_FRIENDS'); ?></a>
          </div>
        <?php
			if($enablegroups) 
			{
			?>					
        <div class="hellome-icon hellome-group">
          <!--<i class="com-icon-groups"></i>-->
          
          <a  href="<?php echo CRoute::_('index.php?option=com_community&view=groups&task=mygroups&userid='.$myId); ?>"><?php echo JText::_('MOD_HELLOME_MY_GROUPS'); ?></a>
          </div>
        <?php
			}
			?>
        <?php
			if($enablephotos) 
			{
			?>
        <div class="hellome-icon hellome-photo">
          <!--<i class="com-icon-photos"></i>-->
          
          <a  href="<?php echo CRoute::_('index.php?option=com_community&view=photos&task=myphotos&userid='.$myId); ?>"><?php echo JText::_('MOD_HELLOME_MY_PHOTOS'); ?></a>
          </div>
        <?php
			}
			?>
        <?php
			if($enablevideos) 
			{
			?>
        <div class="hellome-icon hellome-video">
          <!--<i class="com-icon-videos"></i>-->
          
          <a  href="<?php echo CRoute::_('index.php?option=com_community&view=videos&task=myvideos&userid='.$myId); ?>"><?php echo JText::_('MOD_HELLOME_MY_VIDEOS'); ?></a>
          </div>
        <?php
			}
			?>
        <?php
			if($enableevents) 
			{
			?>
        <div class="hellome-icon hellome-event">
          <!--<i class="com-icon-events"></i>-->
          
          <a  href="<?php echo CRoute::_('index.php?option=com_community&view=events&task=myevents&userid='.$myId); ?>"><?php echo JText::_('MOD_HELLOME_MY_EVENTS'); ?></a>
          </div>
        <?php
			}
			?>
        <?php
			if ($show_myblog) 
			{
				if (file_exists(JPATH_ROOT."/components/com_myblog/functions.myblog.php"))
				{
					include_once JPATH_ROOT."/components/com_myblog/functions.myblog.php";
					$myblogItemId = myGetItemId();
			?>
        <!-- <div style="background: transparent url(<?php echo JURI::root(); ?>modules/mod_hellome/images/icons-16x16.gif) no-repeat 0 -338px; padding: 0 0 0 22px;">
						<a style="line-height: 18px;" href="<?php echo JRoute::_('index.php?option=com_myblog&blogger='. $myName .'&Itemid='.$myblogItemId); ?>"><?php echo JText::_('MOD_HELLOME_MYBLOGS'); ?></a>
					</div> -->
        <?php
				}
			}
			?>
        
        <div class="hellome-icon hellome-logout">
          <!--<i class="com-icon-door-out"></i>-->
          
          <a  href="javascript:void(0);" onclick="helloMe.logout();"><?php echo JText::_('MOD_HELLOME_MY_LOGOUT'); ?></a>
          </div>
        </div><!--.cHello-Menu-->
      
      
      <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="hellomelogout" id="hellomelogout">	
        <input type="hidden" name="option" value="<?php echo COM_USER_NAME;?>" />
        <input type="hidden" name="task" value="<?php echo COM_USER_TAKS_LOGOUT;?>" />
        <input type="hidden" name="return" value="<?php echo $uri; ?>" />
        <?php echo JHtml::_('form.token'); ?>
        </form>
      <?php
}
else
{
	$content = '';
	
	if ($params->get('enable_login', '1'))
	{
		$uri  = CRoute::_('index.php?option=com_community&view='.$config->get('redirect_login') , false );
		$uri  = base64_encode($uri);
		$html = '';

		if (JPluginHelper::isEnabled('authentication', 'openid')) 
		{
			JHTML::_('script', 'openid');
		}
?>
      <form action="<?php echo CRoute::_( 'index.php?option='.COM_USER_NAME.'&task='.COM_USER_TAKS_LOGIN ); ?>" method="post" name="form-login" id="form-login" >
        <?php echo $params->get('pretext'); ?>
        <fieldset class="userdata">
          <div id="form-login-username" class="control-group">
            <div class="controls">
				<div  class="input-prepend">
					
					<span class="add-on">
						<i class="icon-user tip" title="User Name"></i>
					</span>
				
						
					  <input name="username" id="username" type="text" class="inputbox" alt="username" placeholder="<?php echo JText::_('BT_EDUCATION_MOD_LOGIN_VALUE_USERNAME') ?>" size="18" />
				</div>
              </div>
          </div>
          <div id="form-login-password" class="control-group">
            <div class="controls">
				<div  class="input-prepend">
					<span class="add-on">
							<i class="icon-lock tip" title="Password"></i>
					</span>
					<input type="password" name="<?php echo COM_USER_PASSWORD_INPUT;?>" id="passwd" class="inputbox" size="18" placeholder="<?php echo JText::_('BT_EDUCATION_MOD_LOGIN_VALUE_PASSWORD') ?>" alt="password" />
				</div>
              </div>
          </div>
          <?php if (JPluginHelper::isEnabled('system', 'remember')) { ?>
          <div id="form-login-remember" class="control-group">
            <label for="remember">
				<input type="checkbox" name="remember" id="remember" value="yes" alt="Remember Me" />
				<?php echo JText::_('COM_COMMUNITY_REMEMBER_MY_DETAILS') ?>
              
              </label>
          </div>
          <?php } ?>
          <div class="control-group"><input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('LOGIN') ?>" /></div>
          </fieldset>
        <div class="hellome-login-forgot">
          <div>
            <a class="forgot-pass" href="<?php echo JRoute::_( 'index.php?option='.COM_USER_NAME.'&view=reset' ); ?>">
            <?php echo JText::_('MOD_HELLOME_FORGOT_YOUR_PASSWORD'); ?>
            </a>
            </div>
          <div>
            <a class="forgot-ussername" href="<?php echo JRoute::_( 'index.php?option='.COM_USER_NAME.'&view=remind' ); ?>">
            <?php echo JText::_('MOD_HELLOME_FORGOT_YOUR_USERNAME'); ?>
            </a>
            </div>
          <div>
            <a class="resend-code" href="<?php echo CRoute::_( 'index.php?option=com_community&view=register&task=activation' ); ?>" class="login-forgot-username">
            <span><?php echo JText::_('MOD_HELLOME_RESEND_ACTIVATION_CODE'); ?></span>
            </a>
            </div>
          <?php

				$usersConfig=JComponentHelper::getParams( 'com_users' );
				
				if ($usersConfig->get('allowUserRegistration')) 
				{ ?>
          <div>
            <a href="<?php echo CRoute::_( 'index.php?option=com_community&view=register' ); ?>">
              <?php echo JText::_('MOD_HELLOME_REGISTER'); ?>
            </a>
            </div>
          <?php } ?>
          </div>
        <?php echo $params->get('posttext'); ?>
        
        <input type="hidden" name="option" value="<?php echo COM_USER_NAME;?>" />
        <input type="hidden" name="task" value="<?php echo COM_USER_TAKS_LOGIN;?>" />
        <input type="hidden" name="return" value="<?php echo $uri; ?>" />
        <?php echo JHTML::_('form.token'); ?>
        </form>
      <?php
	}
	
	if ($params->get('enable_facebookconnect', '1'))
	{
		/** detect and display facebook language **/
		defined('FACEBOOK_LANG_AVAILABLE') or define('FACEBOOK_LANG_AVAILABLE', 1);

		$lang       =JFactory::getLanguage();
		$currentLang =  $lang->get('tag');
		$fbLang      =   explode(',', trim(FACEBOOK_LANGUAGE) );
		$currentLang = str_replace('-','_',$currentLang);
		$fbLangCode  = '//connect.facebook.net/en_GB/all.js';

		if (in_array($currentLang, $fbLang) == FACEBOOK_LANG_AVAILABLE)
		{
		    $fbLangCode = '//connect.facebook.net/'.$currentLang.'/all.js';
		}

		if ($my->id == 0)
		{
			if ($config->get('fbconnectkey') && $config->get('fbconnectsecret'))
			{
		?>
      <div id="fb-root"></div>
      
      <script type="text/javascript">
			function cFbInit(){
					// keep looping until user status is not 'notConnected'
					if( typeof window.FB != 'undefined'
						&& window.FB._apiKey != '<?php echo $config->get('fbconnectkey');?>' 
						&& typeof window.jomFbinit == 'function' ){
						jomFbinit();
					}
					else
					{ 
						setTimeout("cFbInit();", 500);  
					}
				}

			cFbInit();
			</script>
      <script type="text/javascript" src="<?php echo $fbLangCode; ?>" ></script>
      <script type="text/javascript">
			function jomFbButtonInit(){
				FB.init({
					appId: '<?php echo $config->get('fbconnectkey');?>', 
					status: true, 
					cookie: true, 
					oauth: true,
					xfbml: true
					});
				}
				
			if( typeof window.FB != 'undefined' ) {
				jomFbButtonInit();
			} else {
				window.fbAsyncInit = jomFbButtonInit;
			}
			</script>
      <fb:login-button onlogin="joms.connect.update();" scope="read_stream,publish_stream,offline_access,email,user_birthday,status_update,user_status"><?php echo JText::_('COM_COMMUNITY_SIGN_IN_WITH_FACEBOOK');?></fb:login-button>
      <?php
			}
		}
	}
}
?>
    </div>
  </div>
</div>
