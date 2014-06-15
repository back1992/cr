<?php
/**
 * @package 	bt_socialconnect - BT Social Connect Component
 * @version		1.0.0
 * @created		February 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2014 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 
defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/widgets/btwidget.php');

class Social_share extends BTWidget {	
	
	public static function display($params){
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::root()  . 'components/com_bt_socialconnect/assets/css/bt_socialbublish.css'); 
	$doc = JFactory::getDocument();
	$url    = JURI::getInstance();
	$url    = $url->toString();
	$title  = $doc->getTitle();
	$title  = htmlentities($title, ENT_QUOTES, "UTF-8");		
	$html ='<div class="bt-social-share bt-social-share">';
	//Add all share button
		if($params->get('add_button',1)==1){
			$html .=self::getAddThisButton($params);
		}
	// Facbook share button//
		if($params->get('facebook_share_button',1)==1){
			$html .=self::getFacebookShareButton($params,$url, $title);
		}  
	//Facebook like send//
		if($params->get('facebook_like',1)==1){
			$html .=self::getFacebookLikeSend($params,$url, $title);
		}
	//Twitter button //
		if($params->get('twitter',1)==1){
			$html .=self::getTwitterButton($params,$url, $title);
		}
	//Buffer button
		if($params->get('bufferButton',1)==1){
			$html .=self::getBufferButton($params,$url, $title);
		}
	//Google plus
		if($params->get('googleplus',1)==1){
			$html .=self::getGoogleButton($params,$url, $title);
		}
	//Linkedin button	
		if($params->get('linkedin',1)==1){
			$html .=self::getLinkedinButton($params,$url, $title);
		}
	//Linkedin Follow button	
		if($params->get('linkedinfollow',1)==1){
			$html .=self::getLinkedinFollowButton($params,$url, $title);
		}
	//Linkedin Recommend button	
		if($params->get('linkedin_recommend',1)==1){
			$html .=self::getLinkedinRecommendButton($params,$url, $title);
		}
	//Printeres button	
		if($params->get('printeres',1)==1){
			$html .=self::getPrinteresButton($params,$url, $title);
		}
	//Stumble button	
		if($params->get('stumble',1)==1){
			$html .=self::getStumbleButton($params,$url, $title);
		}
	//Digg button	
		if($params->get('digg',1)==1){
			$html .=self::getDiggButton($params,$url, $title);
		}
		$html .='</div>';
        return $html;
    }
	
	public static function getAddThisButton($params){
		$html =array();
		$html[] ='<div class="bt-social-share-button addthis_toolbox addthis_default_style ">';
		if($params->get('button_style','lg-share-counter')=='lg-share-counter'){
			$html[]='<a class="addthis_counter addthis_pill_style"></a>';
		}
		if($params->get('button_style','lg-share-counter')=='lg-small'){
		$html []='<a class="addthis_button_compact"></a>';

		}
		if($params->get('button_style','lg-share-counter')=='lg-share'){
			$html []=' <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=ra-51cbb46a34c2b36f"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>';
		}
		$html []='</div>';
		$html []='<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>';
		$html []='<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid='.$params->get('profile_id','').'"></script>';

		return implode($html);
	}
	
	public static function getFacebookShareButton ($params,$url,$title){
		$html =array();
		$buttonType = $params->get('facebook_share_button_type');
		$html[] = '<div class="bt-social-share-button bt-facebook-share-button">';
        if ($buttonType) {
            $html[] = '<fb:share-button href="' . $url . '" type="' . $buttonType . '"></fb:share-button>';
        } else {
            $html[]= '<img class="fb-share" src="' . JURI::root() . 'components/com_bt_socialconnect/images/share.png" onClick="window.open(\'http://www.facebook.com/sharer.php?u=\'+encodeURIComponent(\'' . $url . '\')+\'&t=\'+encodeURIComponent(\'' . $title . '\'),\'sharer\',\'toolbar=0,status=0,left=\'+((screen.width/2)-300)+\',top=\'+((screen.height/2)-200)+\',width=600,height=360\');" href="javascript: void(0)" />';
        }
        $html[]= '</div>';
		return implode($html);
	}
	
	public static function getFacebookLikeSend($params,$url,$title){	 
     $html = array();
        $html[] = '<div class="bt-social-share-button bt-facebook-like-button">';       
            $html[] = '<div class="fb-like" data-href="' . $url;
            $html[] = '" data-colorscheme="' . $params->get('facebook_like_color','light');
            $html[] = '" data-font="' . $params->get('facebook_like_font','arial');
            $html[] = '" data-send="' . ($params->get('facebook_sendbutton',0) == 1 ? "true" : "false");
            $html[] = '" data-layout="' . $params->get('facebook_like_type','button_count');
            $html[] = '" data-width="' . $params->get('facebook_like_width',70);
            $html[] = '" data-show-faces="' . ($params->get('facebook_showface') == 1 ? "true" : "false");
            $html[] = '" data-action="' . $params->get('facebook_like_action','like') . '"></div>';       
        $html[] = '</div>';
        return implode($html);	
	}
	
	public static function getTwitterButton($params,$url,$title){
		$html =array();
		$html[]  = '<div class="bt-social-share-button bt-twitter-button" style="width:'.$params->get('twitter_width',80) .'px">';
        $html[]= '<a href="http://twitter.com/share" class="twitter-share-button" 
						  data-via="' . $params->get('twitter_name') . '" 
						  data-text="' . $title . '"
						  data-url="' . $url . '" 
						  data-size="' . $params->get('twitter_size','medium') . '"
						  data-lang="' . $params->get('twitter_language','en') . '"
						  data-count="' . $params->get('twitter_counter','horizontal') . '" >Twitter</a>';
        $html[]= '</div>';
        $html[]= '<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>';
        return implode($html);	
	}
	
	public static function  getBufferButton($params,$url,$title){	 
     $html = array();
	 $picture = 'data-picture=""';
         $html[] = '
            <div class="itp-share-buffer">
            <a href="http://bufferapp.com/add" class="bt-social-share-button bt-buffer-button" '.$picture.' data-text="' . $title . '" data-url="'.$url.'" data-count="'.$params->get("bufferType","horizontal").'" data-via="'.$params->get("bufferTwitterName","").'">Buffer</a><script src="//static.bufferapp.com/js/button.js"></script>
            </div>
            ';
       return implode($html);	
	 }
	 
	 public static function getGoogleButton($params,$url,$title){	
		$html =array();
		$html[] = '<div class="bt-social-share-button bt-google-plus" ' .
                'data-action="share" ' .
                ' data-href="' . $url . '"' .
                ' data-annotation="' . $params->get('google_plus_annotation','bubble') . '" ' .
                ($params->get('google_plus_annotation','bubble') == 'vertical-bubble' ? 'data-height="60"' : ('data-height="' . $params->get('google_plus_type',20) . '" ')) .
                ($params->get("google_plus_annotation",'bubble') == 'inline' ? ('data-width="' . $params->get('google_plus_width', 120) . '"') : '') .
                '></div>';
		return implode($html);
	 }
	 
	public static function getLinkedinButton($params,$url,$title){
	$html =array();
	$html[]= '<div class="bt-social-share-button bt-linkedin-button">';
		$html[]= '<script type="IN/share" data-url="' . $url . '"
							 data-showzero="' . ($params->get('linkedIn_showzero',0) == 1 ? "true" : "false") . '"
							 data-counter="' . $params->get('linkedIn_type','right') . '"></script>';
		$html[]= '</div>';
		$html[]= '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
    
	return implode($html);	
	}
	
	public static function getLinkedinFollowButton($params,$url,$title){
	$html =array();
	$html[]= '<div class="bt-social-share-button bt-linkedinfollow-button">';
		$html[]= '<script type="IN/FollowCompany" 							 
							 data-id="' . $params->get('followcompany','') . '"
							 data-counter="' . $params->get('linkedInfollow_type','right') . '"></script>';
		$html[]= '</div>';
		$html[]= '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
    
	return implode($html);	
	}
	
	public static function getLinkedinRecommendButton($params,$url,$title){
	$html =array();
	$html[]= '<div class="bt-social-share-button bt-linkedinrecommend-button">';
		$html[]= '<script type="IN/RecommendProduct" 							 
							 data-company="' . $params->get('company_name','') . '"
							 data-product="' . $params->get('product_id','') . '"
							 data-counter="' . $params->get('linkedInrecommend_type','right') . '"></script>';
		$html[]= '</div>';
		$html[]= '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>';
    
	return implode($html);	
	}
	
	public static function getPrinteresButton($params,$url,$title){
		$html =array();
		$img="http://www.flickr.com/photos/kentbrew/6851755809/";
		$link = '//pinterest.com/pin/create/button/?url='.urlencode($url).'&media='.urlencode($img);
		if($params->get('description'))
		  {
			$link .= '&description='.urlencode($params->get('description',''));
		  }
		$html[]= '<div class="bt-social-share-button bt-printeres-button"><a href="'.$link.'" data-pin-do="buttonPin" data-pin-log="button_pinit"  data-pin-config="'.$params->get('pin_count','above').'"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" /></a></div>';
		$html[]='<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>';
		
		return implode($html);
	}
	
	public static function getStumbleButton($params,$url,$title){
	$html =array();
	$html[] = '<div class="bt-social-share-button bt-stumble-button">
            <script src="http://www.stumbleupon.com/hostedbadge.php?s=' . $params->get("stumble_type",1). '&r=' . rawurlencode($url) . '"></script>
            </div>
            ';        
        
       return implode($html);
	
	}
	
	public static function getDiggButton($params,$url, $title){
		  $html =array();
		  $html[] = '
            <div class="bt-social-share-button bt-digg-button">
				<script type="text/javascript">
				(function() {
				var s = document.createElement(\'SCRIPT\'), s1 = document.getElementsByTagName(\'SCRIPT\')[0];
				s.type = \'text/javascript\';
				s.async = true;
				s.src = \'http://widgets.digg.com/buttons.js\';
				s1.parentNode.insertBefore(s, s1);
				})();
				</script>
				<a 
				class="DiggThisButton '.$params->get("digg_type","DiggCompact") . '"
				href="http://digg.com/submit?url=' . rawurlencode($url) . '&amp;title=' . rawurlencode($title) . '">
				</a>
			</div>';
        
        
          return implode($html);
	
	}
}

?>