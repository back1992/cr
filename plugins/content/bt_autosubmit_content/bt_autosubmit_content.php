<?php
/**
 * @package 	bt_socialconnect_content - BT Social Connect Content
 * @version		1.0.0
 * @created		January 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2014 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die;
set_time_limit(999);
$path = JPATH_ROOT . '/components/com_bt_socialconnect';
if (!is_dir($path))  return;
require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/Autopost.php');	
require_once (JPATH_SITE.'/components/com_content/helpers/route.php');

class plgContentBt_autosubmit_content extends JPlugin
{
	
	public function onContentBeforeDisplay($context, &$row, &$params, $page = 0) {	
	 
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root() . 'components/com_bt_socialconnect/assets/css/bt_socialbublish.css');    
		return;		
	}	
	
	public function onContentAfterSave($context, $article, $isNew)
	{		
		$connten = substr_count($context, 'com_content');
		if ($connten) {
		if(!$isNew) return;
		$caid =0;
		$caid = JHtmlBt_Socialpost::checkCategory($article->catid,$this->params);		
			if($caid > 0){
				//Lay image trong default - imageintro - introtext - fulltext
				$image ='';					
				$introimage =json_decode($article->images);
					if($introimage->image_intro !=''){
						$image=$introimage->image_intro;
					}
					else{
						if($introimage->image_fulltext!=''){
							$image=$introimage->image_fulltext;
						}
						else{
							preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $article->introtext, $match); 	
					
							if(!empty($match[1])){
								$image =$match[1];
							}else{
								preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $article->fulltext, $matches);
								if(!empty($matches[1])){
									$image = $matches[1];
								}
								else{
									$image ='';
								}
							} 
						}
					}
				$array = array();
				$article->image = $image;
				$article->link = ContentHelperRoute::getArticleRoute($article->id, $article->catid);	
				$article->trigger ='Joomla Content';
				$array['params'] = array(
					'query'=>'SELECT count(id) FROM #__content WHERE state =1 AND id='.$article->id
				);
				$parameter = new JRegistry;
				$parameter->loadArray($array['params']);
				$article->params = (string)$parameter;
				
				$params = JComponentHelper::getParams('com_bt_socialconnect');
				$cronjob = $params->get('enabled_cronjobs',1);		
				//Kiem tra neu la conten					
					if ($article->state ==1){
						Bt_SocialconnectHelperAutoPost::postArticle($article,$cronjob,$this->params);
					}else{
						$cronjob = 1;
						Bt_SocialconnectHelperAutoPost::postArticle($article,$cronjob,$this->params);
					}
					
				}
			
		}
		return true;
	}
			  

}

?>