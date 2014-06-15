-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 25, 2012 at 02:00 AM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `news`
--

-- --------------------------------------------------------

--
-- Table structure for table `__bt_users`
--
DROP TABLE IF EXISTS `#__bt_users`;
CREATE TABLE `#__bt_users` (
  `user_id` int(11) NOT NULL,
  `profile_link` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website` varchar(255) NOT NULL,
  `favorite_quotes` varchar(255) NOT NULL,  
  `about` text NOT NULL,
  `gender` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__bt_user_fields`
--
DROP TABLE IF EXISTS `#__bt_user_fields`;
CREATE TABLE `#__bt_user_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `alias` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `default_values` text,
  `ordering` int(11) DEFAULT NULL,
  `description` text,
  `required` tinyint(4) DEFAULT NULL,
  `show_registration` tinyint(4) DEFAULT NULL,
  `show_listing` tinyint(4) DEFAULT NULL,
  `facebook` text,
  `twitter` text,
  `google` text,
  `linkedin` text,
  `published` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__bt_user_fields
-- ----------------------------

INSERT INTO `#__bt_user_fields` VALUES ('1', 'Profile link', 'profile_link', 'string', '', '3', '','0', '1', '1', 'link', 'link', 'link', 'link', '1');
INSERT INTO `#__bt_user_fields` VALUES ('2', 'Avatar', 'avatar', 'image', '', '1', '', '0', '1', '1', 'picture', 'picture', 'picture', 'picture', '1');
INSERT INTO `#__bt_user_fields` VALUES ('3', 'Birthday', 'birthday', 'date', '', '4', '', '0', '1', '1', 'birthday', 'birthday', 'birthday', 'birthday', '1');
INSERT INTO `#__bt_user_fields` VALUES ('4', 'Location', 'location', 'string', 'VietNamese', '5', '', '0', '1', '0', 'location', 'location', 'location', 'location', '1');
INSERT INTO `#__bt_user_fields` VALUES ('5', 'Website', 'website', 'string', '', '8', '', '0', '1', '0', 'website', 'website', '', '', '1');
INSERT INTO `#__bt_user_fields` VALUES ('6', 'Favorite quotes', 'favorite_quotes', 'string', '', '6', '', '0', '1', '0', 'quotes', 'quotes', '', 'quotes', '1');
INSERT INTO `#__bt_user_fields` VALUES ('7', 'About', 'about', 'text', '', '11', '', '0', '1', '1', '', '', '', '', '1');
INSERT INTO `#__bt_user_fields` VALUES ('8', 'Gender', 'gender', 'dropdown', 'a:2:{s:5:"label";s:19:"-- Select Gender --";s:5:"value";a:2:{i:0;s:4:"male";i:1;s:6:"female";}}', '2', '', '0','1', '1', '', '', '', '', '1');
INSERT INTO `#__bt_user_fields` VALUES ('9', 'City', 'city', 'dropdown', 'a:2:{s:5:"label";s:17:"-- Select City --";s:5:"value";a:3:{i:0;s:8:"Chealsea";i:1;s:9:"Liverpool";i:2;s:7:"Asernal";}}', '7', '', '0', '1', '1', '', '', '', '', '1');

--
-- Dumping data for table `#__bt_widgets`
--

DROP TABLE IF EXISTS `#__bt_widgets`;
CREATE TABLE `#__bt_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255)  NOT NULL,
  `alias` varchar(255)  NOT NULL,
  `wgtype` varchar(255) NOT NULL,
  `published` tinyint(4) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `note` text ,
  `params` text ,
  `updated_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of #__bt_widgets
-- ----------------------------
INSERT INTO `#__bt_widgets` VALUES ('1', 'Facebook Commend box', 'facebook-commend', 'facebook_commend', '1', '2', '', '{\"fbrendering\":\"html5\",\"fbwidth\":\"470\",\"fbnumber_post\":\"10\",\"fbcolorscheme\":\"light\",\"fborder_by\":\"social\"}', '2013-08-29 04:16:33');
INSERT INTO `#__bt_widgets` VALUES ('2', 'Twitter feed', 'twitter-feed', 'twitter_feed', '1', '1', '', '{\"embed_code\":\"<a class=\\\"twitter-timeline\\\"  href=\\\"https:\\/\\/twitter.com\\/BowThemes\\\"  data-widget-id=\\\"322965533848903680\\\">Tweets by @BowThemes<\\/a>\\r\\n<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=\\/^http:\\/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\\\":\\/\\/platform.twitter.com\\/widgets.js\\\";fjs.parentNode.insertBefore(js,fjs);}}(document,\\\"script\\\",\\\"twitter-wjs\\\");<\\/script>\\r\\n\",\"tweet-limit\":\"3\",\"width\":\"\",\"height\":\"\",\"theme\":\"\",\"link-color\":\"\",\"border-color\":\"\",\"noheader\":\"0\",\"nofooter\":\"0\",\"noborder\":\"0\",\"noscrollbar\":\"0\",\"transparent\":\"0\"}', '2013-10-07 01:49:09');
INSERT INTO `#__bt_widgets` VALUES ('3', 'Social share button', 'social-share-button', 'social_share', '1', '3', '', '{\"add_button\":\"1\",\"profile_id\":\"Your Profile ID\",\"button_style\":\"lg-share-counter\",\"facebook_share_button\":\"1\",\"facebook_share_button_type\":\"\",\"facebook_like\":\"1\",\"facebook_sendbutton\":\"1\",\"facebook_like_type\":\"button_count\",\"facebook_like_width\":\"70\",\"facebook_showface\":\"1\",\"facebook_like_font\":\"arial\",\"facebook_like_color\":\"light\",\"facebook_like_action\":\"like\",\"twitter\":\"1\",\"twitter_name\":\"BowThemes\",\"twitter_counter\":\"horizontal\",\"twitter_size\":\"medium\",\"twitter_language\":\"en\",\"twitter_width\":\"80\",\"bufferButton\":\"1\",\"bufferType\":\"horizontal\",\"bufferTwitterName\":\"BowThemes\",\"googleplus\":\"1\",\"google_plus_annotation\":\"bubble\",\"google_plus_width\":\"120\",\"google_plus_type\":\"20\",\"linkedin\":\"1\",\"linkedIn_type\":\"none\",\"linkedIn_showzero\":\"1\",\"linkedinfollow\":\"1\",\"followcompany\":\"3129602\",\"linkedInfollow_type\":\"right\",\"linkedInfollow_showzero\":\"1\",\"linkedin_recommend\":\"1\",\"company_name\":\"Bowthemes\",\"product_id\":\"201714\",\"linkedInrecommend_type\":\"right\",\"printeres\":\"1\",\"pin_count\":\"beside\",\"description\":\"\",\"stumble\":\"1\",\"stumble_type\":\"1\",\"digg\":\"1\",\"digg_type\":\"DiggCompact\"}', '2013-10-07 01:50:35');
INSERT INTO `#__bt_widgets` VALUES ('4', 'Google Interactive posts', 'google-interactive-posts', 'google_interactive_posts', '1', '4', '', '{\"client_id\":\"306040699264\",\"conten_url\":\"\",\"cookiepolicy\":\"uri\",\"gg_label\":\"CREATE\",\"calltoactionurl\":\"http:\\/\\/plus.google.com\\/pages\\/create\",\"calltoactiondeeplinkid\":\"\",\"prefilltext\":\"Come learn about interactive posts with me!\",\"button_text\":\"Invite your friends!\",\"contentdeeplinkid\":\"\"}', '2013-08-27 09:02:44');
INSERT INTO `#__bt_widgets` VALUES ('5', 'Login button', 'login-button', 'login_button', '1', '5', '', '{\"buttonlogin\":[\"all\",\"facebook\",\"twitter\",\"google\",\"linkedin\"]}', '2013-09-09 04:12:49');
INSERT INTO `#__bt_widgets` VALUES ('6', 'Facebook Recommendations bar', 'facebook-recommendations-bar', 'facebook_recommendations_bar', '1', '7', '', '{\"rendering\":\"xfbml\",\"fb_url\":\"https:\\/\\/www.facebook.com\\/bowthemes?fref=ts\",\"fbtrigger\":\"X%\",\"fbread_time\":\"30\",\"fbflike_action\":\"like\",\"fbside\":\"right\",\"fbdomain\":\"\",\"fbnum_recommendations\":\"2\"}', '2013-09-18 04:19:30');
INSERT INTO `#__bt_widgets` VALUES ('7', 'Facebook Embedded Posts', 'facebook-embedded-posts', 'facebook_embedded_post', '1', '8', '', '{\"urlembed\":\"https:\\/\\/www.facebook.com\\/FacebookDevelopers\\/posts\\/10151471074398553\"}', '2013-08-30 06:48:57');
INSERT INTO `#__bt_widgets` VALUES ('8', 'Facebook Activity Feed', 'facebook-activity-feed', 'facebook_activityfeed', '1', '9', '', '{\"fbdomain\":\"\",\"fbRendering\":\"2\",\"fbappid\":\"\",\"fbaction\":\"\",\"fbwidth\":\"300\",\"fbheight\":\"300\",\"fbshowheader\":\"true\",\"fbcolorscheme\":\"light\",\"fblinktarget\":\"_blank\",\"fbfont\":\"\",\"fbbordercolor\":\"\",\"fbrecommendations\":\"false\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\"}', '2013-08-30 07:11:14');
INSERT INTO `#__bt_widgets` VALUES ('9', 'Linkedin member profile', 'linkedin-member-profile', 'linkedin_memberprofile', '1', '10', '', '{\"profile_url\":\"http:\\/\\/www.linkedin.com\\/pub\\/anh-thong\\/7b\\/377\\/b86\",\"layout\":\"iconname\",\"textname\":\"THong enino\",\"event\":\"hover\",\"connection\":\"true\"}', '2013-09-09 06:57:32');
INSERT INTO `#__bt_widgets` VALUES ('10', 'Linkedin company Insider', 'linkedin-company-insider', 'linkedin_company_insider', '1', '11', '', '{\"company_id\":\"3129602\",\"view\":[\"innetwork\",\"newhires\",\"jobchanges\"]}', '2013-09-11 03:06:09');
INSERT INTO `#__bt_widgets` VALUES ('11', 'Linkedin company profile', 'linkedin-company-profile', 'linkedin_companyprofile', '1', '12', '', '{\"company_name\":\"Bowthemes\",\"layout\":\"inline\",\"textname\":\"Vsmarttech\",\"event\":\"click\",\"connection\":\"false\"}', '2013-09-11 03:08:09');
INSERT INTO `#__bt_widgets` VALUES ('12', 'Linkedin Apply button', 'linkedin-apply-button', 'linkedin_apply_button', '1', '13', '', '{\"company_id\":\"3129602\",\"rcemail\":\"anhthonghn@gmail.com\",\"jobtitle\":\"student\",\"joblocation\":\"developer\",\"companylogo\":\"http:\\/\\/bowthemes.com\\/templates\\/bowthemes\\/images\\/joomla_templates_logo.png\",\"themecolor\":\"f08024\",\"phone\":\"0\"}', '2013-10-07 01:55:10');
INSERT INTO `#__bt_widgets` VALUES ('13', 'Linkedin Build a Jobs', 'linkedin-build-a-jobs', 'linkedin_job', '1', '14', '', '{\"job\":\"0\",\"company_id\":\"3129602\"}', '2013-09-09 10:34:00');
INSERT INTO `#__bt_widgets` VALUES ('14', 'Facebook Facepile', 'facebook-facepile', 'facebook_facepile', '1', '15', '', '{\"fbrendering\":\"html5\",\"fburl\":\"http:\\/\\/facebook.com\\/FacebookDevelopers\",\"fbaction\":\"30\",\"fbnum_row\":\"30\",\"fbsize\":\"large\",\"fbshowcount\":\"true\",\"fbwidth\":\"300\",\"fbcolorscheme\":\"light\"}', '2013-09-11 09:20:50');
INSERT INTO `#__bt_widgets` VALUES ('15', 'Facebook Recommendations box', 'facebook-recommendations-box', 'facebook_recommendations_box', '1', '16', '', '{\"fbrendering\":\"html5\",\"fbdomain\":\"developers.facebook.com\",\"fbapp_id\":\"\",\"fbaction\":\"\",\"fbwidth\":\"300\",\"fbheight\":\"300\",\"fbshowheader\":\"true\",\"fbcolorscheme\":\"light\",\"fblinktarget\":\"_blank\",\"fbfont\":\"\",\"fbmax_age\":\"0\"}', '2013-10-07 01:37:50');
INSERT INTO `#__bt_widgets` VALUES ('16', 'Facebook Like Box', 'facebook-like-box', 'facebook_likebox', '1', '17', '', '{\"type\":\"likebox\",\"profile\":\"\",\"href\":\"http:\\/\\/www.facebook.com\\/bowthemes\",\"width\":\"\",\"height\":\"556\",\"height_follow\":\"\",\"layout\":\"standard\",\"font\":\"\",\"show_faces\":\"true\",\"connections\":\"10\",\"show_stream\":\"true\",\"show_header\":\"true\",\"force_wall\":\"false\",\"colorscheme\":\"light\",\"border_color\":\"\",\"moduleclass_sfx\":\"\",\"cache\":\"1\",\"cache_time\":\"900\",\"cachemode\":\"static\"}', '2013-10-07 01:42:21');
INSERT INTO `#__bt_widgets` VALUES ('17', 'Google Badge', 'google-badge', 'google_badge', '1', '18', '', '{\"page_id\":\"110185082550651520481\",\"badge_size\":\"badge\",\"customize_name\":\"Bowthemes\",\"alt_icon\":\"BowThemes Google plus Page\",\"theme\":\"light\",\"height\":\"69\",\"width\":\"300\",\"badge_tag\":\"htmlvalid\",\"async\":\"1\",\"parse_tags\":\"onload\"}', '2013-10-07 01:43:47');
INSERT INTO `#__bt_widgets` VALUES ('18', 'Google Comment', 'google-comment', 'google_comment', '1', '19', '', '{"width":"650"}', '2013-12-16 04:36:57');

--
-- Dumping data for table `#__bt_channels`
--
DROP TABLE IF EXISTS `#__bt_channels`;
CREATE TABLE `#__bt_channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `type` varchar(225)  DEFAULT NULL,
  `author` varchar(255)  DEFAULT NULL,
  `version` varchar(255)  DEFAULT NULL,
  `description` text ,
  `params` text ,
  `published` tinyint(4) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__bt_messages`
--

DROP TABLE IF EXISTS `#__bt_messages`;
CREATE TABLE `#__bt_messages` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdby` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `title` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  `message` text,
  `trigger` varchar(20) DEFAULT NULL,
  `published` tinyint(4) DEFAULT NULL,
  `log` text,
  `event` int(11) NOT NULL,
  `scron` text,
  `schedule` varchar(250) NOT NULL,
  `sent_time` datetime DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `chanel_id` int(11) NOT NULL,
  `message_type` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `#__bt_connections`
--

DROP TABLE IF EXISTS `#__bt_connections`;
CREATE TABLE  `#__bt_connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `social_id` varchar(255) NOT NULL,
  `social_type` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `params` text NOT NULL,
  `enabled_publishing` tinyint(4) DEFAULT NULL,
  `created_time` datetime NOT NULL,
  `updated_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

