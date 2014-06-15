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
 
defined('_JEXEC') or die;

abstract class JHtmlBt_Socialpost{


	public static function checkCategory($catid ,$params) {
		
		$array =array();
		$category =$params->def('conten_categories', 0);		
		if(count($category)== 1 && $category[0]== 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);		
			$query->select('id');
			$query->from('#__categories');		
			$db->setQuery($query);
			$result = $db->loadObjectList();
			foreach($result As$key=>$item){
				$array[] =$item->id;
			}			
		}else{
			$array = $category;
		}		
		$count=0;
		foreach($array AS $key=>$item){
			if($item ==$catid){
			$count++;
			}
		}
		return $count;
	  }
	  
	   public static function CheckK2Category($catid ,$params) {

		$k2array =array();
		$k2category = $params->def('k2_category',0);	
		
		if(count($k2category)== 1 && $k2category[0]== 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);		
			$query->select('id');
			$query->from('#__k2_categories');		
			$db->setQuery($query);
			$result = $db->loadObjectList();
			foreach($result As$key=>$item){
				$k2array[] =$item->id;
			}
			
		}else{
			$k2array =$k2category;
		}		
		$count =0;		
		foreach($k2array AS $key=>$item){
			if($item ==$catid){
				$count++;
			}
		}
		return $count;
	  }	
	  
	public static function getpublish($value){
	
		$db = JFactory::getDbo();
		$db->setQuery("SELECT `id`,`alias`,`type` from #__bt_channels WHERE published =1 ".self::getCondition($value));
		$result= $db->loadObjectList();	
		return $result;
	}
	
	public static function getCondition($option) {	
		$sql = '';
		if ((count($option) == 1) &&($option[0] == 0)) {
             $sql = '';
         } else {
                     
            $sql =  " AND id IN(".implode(',', $option).")";
       }
	   return $sql;

	}
	
	public static function getSocialpostuser($id){
			$db = JFactory::getDbo();
			$db->setQuery('SELECT social_id,social_type,access_token FROM #__bt_connections WHERE user_id = '.$id.' AND enabled_publishing =1');
			$items = (array) $db->loadObjectList();
			return $items;
	
	}
	
	public static function getUser($socialid){
		$user='';
		$db = JFactory::getDbo();
		$db->setQuery('SELECT `user_id` FROM #__bt_connections WHERE `social_id` = "'.$socialid.'"');
		$items = $db->loadObject();		
		if(!empty($items)){
			$db->setQuery('SELECT name FROM #__users WHERE id = '.(int)$items->user_id);
			$result = $db->loadResult();
			$user = $result;
		}
		return $user;
	}
	
	public static function processmessage($message){
		
		$html='<div style="padding:5px 0 5px 5px;line-height:20px;">';
		$html .='<div style="display:block; padding:0 0 5px 0px">';
		if(isset($message['message'])){
			$html .=$message['message'];
		}		
		$html .='</div>';
		if(isset($message['picture'])){
			$html .='<img src="'.$message['picture'].'" alt="" style="max-width:100px;float:left;margin-right:10px;"/>';
		}		
		if(isset($message['link'])){
			$html .='<a class="link_article" href="'.$message['link'].'" target="_blank">'.$message['name'].'</a><br/>';
		}		
		if(isset($message['description'])){
			$html .=$message['description'];
		}		
		$html .='</div>';		
		return $html;	
		
	}	

	public static function getContentUrl($url) {
	
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
	  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	  curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	  curl_setopt($ch, CURLOPT_AUTOREFERER, false);
	  curl_setopt($ch, CURLOPT_REFERER, 'http://google.com');
	  curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	  curl_setopt($ch, CURLOPT_HEADER, 0);
	  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);    // Follows redirect responses
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	  $file = curl_exec($ch);
	  if($file === false) trigger_error(curl_error($ch));

	  curl_close ($ch);
	  return $file;
	}
	
	public static function socialPost($type,$attachment, $url , $user , $socialid){
		
		$message =array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		switch ($type){
		case'facebook':
			$go = curl_exec($ch);
			$page = json_decode($go);
			if(isset($page->error)){
			$log =$page->error->message;
			$publish =0;
			}else{
				$log=JTEXT::_('Message has sent '.'<a href="https://www.facebook.com/'.$socialid.'" style="text-decoration: underline;" target="_blank">'.$user.'</a>'.' successfully');
			$publish = 1;
				}
			curl_close ($ch);	
			$message['log']=$log;
			$message['publish']=$publish;
			break;
		
		case'linkedin':
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			$go = curl_exec($ch);
			$page = json_decode($go);
			if(isset($page->error)){
				$log =$page->error->message;
				$publish =0;
			}else{
				$log=JTEXT::_('Message has sent '.'<a href="http://www.linkedin.com/profile/view?id='.$socialid.'" style="text-decoration: underline;" target="_blank">'.$user.'</a>'.' successfully');
				$publish = 1;
			}
			curl_close ($ch);	
			$message['log']=$log;
			$message['publish']=$publish;
			break;			
		}
		return $message;
	}
	
	public static function fetch($accesstoken, $body = '') {
       $response =array();
        $params = array(
            'oauth2_access_token' => $accesstoken,
            'format' => 'json',
        ); 		
		
        $urlInfo = parse_url('https://api.linkedin.com/v1/people/~/shares'); 
		
        if(isset($urlInfo['query'])){
            $query = parse_str($urlInfo['query']);
            $params = array_merge($params,$query);
        }    
     
        $url = 'https://api.linkedin.com' . $urlInfo['path'] . '?' . http_build_query($params);        
		
        if(!is_string($body)){           
                $body = json_encode($body);                     
        }
		$response['url'] =$url;        
		$response['body'] =$body;          
       
        return $response;
    }
	
	public static function  createFolder($path){
		if (!is_dir($path))
		{
			JFolder::create($path, 0755);
			$html = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write($path.'index.html', $html);
		}
	}
	
	public static function cropimage($urlimage,$images_path,$params){
		
		return self::renderThumb($urlimage,$params->get('thumbwidth',180),$params->get('thumbheight',110),$images_path);
	}
	
	public function renderThumb($path, $width, $height, $images_path) {
		
			$path = str_replace(JURI::base(), '', $path);
			$imagSource = JPATH_SITE . '/' . $path;
			$imagSource = urldecode($imagSource);
			$tmp = explode('/', $imagSource);
			$imageName = md5($path.$width.$height).'-'. $tmp[count($tmp) - 1];
			$thumbPath = $images_path . $imageName;
			if (file_exists($imagSource)) {	
				if (!file_exists($thumbPath)) {
					//create thumb
					self::createImage($imagSource, $thumbPath, $width, $height, true);
				}
				return  $imageName;
			}else{
				
				 if (!file_exists($thumbPath)){
					 // Try to load image from external source
					 // Image loaded?
					 if (self::_CreateImageUsingCurl( $path, $thumbPath, 5 )) {
						 self::createImage($thumbPath, $thumbPath, $width, $height, true);
						 return  $imageName;
					 }
				 } else {
					 return $imageName;
				 }
			}
		
		//return path
		return $imageName;
	}
	
	private static function _CreateImageUsingCurl( $url,$thumbPath, $maxImageLoadTime )
	{
		$curl = false;
		if ( function_exists( 'curl_init' ) )
		{
			$curl = curl_init();
		}
		if ( $curl )
		{
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_HEADER, false );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, $maxImageLoadTime );
			//curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $curl, CURLOPT_MAXREDIRS, 11 /*just a number that seems plenty enough*/ );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER,  FALSE );
			$contents = curl_exec( $curl );
			curl_close( $curl );
			JFile::write($thumbPath,$contents);
			return true;
		}
		return false;
	}
	
	static function createImage($imgSrc, $imgDest, $width, $height, $crop = true, $quality = 100) {
			if (JFile::exists($imgDest)) {
				$info = getimagesize($imgDest, $imageinfo);
				// Image is created
				if (($info[0] == $width) && ($info[1] == $height)) {
					return;
				}
			}
		self::resize($imgSrc, $width, $height);			

		}
	
	public static function resize($path,$width,$height){
		
		$src = $path;
		$dst = $path;				
		$info = getimagesize($path, $imageinfo);
		if($info){
			$ext = str_replace('image/', '', $info['mime']);
			$imageCreateFunc = self::getImageCreateFunction($ext);
			$w = $info[0];
			$h = $info[1];  
			$img = $imageCreateFunc($src); 
			
			if($w < $width or $h < $height) return "Picture is too small!";
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
			 

			$new = imagecreatetruecolor($width, $height);

		  // preserve transparency
			  if($ext == "gif" or $ext == "png"){
				imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
				imagealphablending($new, false);
				imagesavealpha($new, true);
			  }
			  
				imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
				$imageSaveFunc = self::getImageSaveFunction($dst);
				$imageSaveFunc($new, $dst);
		}
	return true;
	}
	
	static function getImageCreateFunction($type) {
			switch ($type) {
				case 'jpeg':
				case 'jpg':
					$imageCreateFunc = 'imagecreatefromjpeg';
					break;

				case 'png':
					$imageCreateFunc = 'imagecreatefrompng';
					break;

				case 'bmp':
					$imageCreateFunc = 'imagecreatefrombmp';
					break;

				case 'gif':
					$imageCreateFunc = 'imagecreatefromgif';
					break;

				case 'vnd.wap.wbmp':
					$imageCreateFunc = 'imagecreatefromwbmp';
					break;

				case 'xbm':
					$imageCreateFunc = 'imagecreatefromxbm';
					break;

				default:
					$imageCreateFunc = 'imagecreatefromjpeg';
			}

			return $imageCreateFunc;
		}

	static function getImageSaveFunction($type) {
			switch ($type) {
				case 'jpeg':
					$imageSaveFunc = 'imagejpeg';
					break;

				case 'png':
					$imageSaveFunc = 'imagepng';
					break;

				case 'bmp':
					$imageSaveFunc = 'imagebmp';
					break;

				case 'gif':
					$imageSaveFunc = 'imagegif';
					break;

				case 'vnd.wap.wbmp':
					$imageSaveFunc = 'imagewbmp';
					break;

				case 'xbm':
					$imageSaveFunc = 'imagexbm';
					break;

				default:
					$imageSaveFunc = 'imagejpeg';
			}

			return $imageSaveFunc;
	}
		
	public static function getcaseshortlink($url){	
		$params = JComponentHelper::getParams('com_bt_socialconnect');	
		$items = $params->get('shorturl','bitly');
		$link ='';		
		switch ($items){
		 case'bitly':
			$bitly_login =$params->get('bitly_login','');
			$bitly_apikey =$params->get('bitly_apikey','');
			$link = self::shorten_url($url,$bitly_login,$bitly_apikey);
			break;			
		case'google':
			$googleapiKey =$params->get('google_apikey','');
			$link =self::shortgoogle($url,$googleapiKey);
			break;			
		case'tiny':
			$link =self::shorttinyurl($url);
			break;
		default:
		 
			$link = $url;
			break;		
		}
		return $link;

	}
	
	public static function shorten_url($url,$bitly_username,$bitly_api_key) {
	
		if (!$url) { return false; }    
		$url = urlencode(trim($url));
		$api_address = 'http://api.bitly.com/v3/shorten?login='.$bitly_username.'&apiKey='.$bitly_api_key.'&longUrl='.$url.'&format=txt';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_address);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		if (!$response) { return false; }
		elseif (substr($response,0,7) != 'http://') { return false; }
		else { return trim(strip_tags($response)); }
	
	}
	
	public static function shortgoogle($url,$apiKey){
		$postData = array('longUrl' => $url, 'key' => $apiKey);
		$jsonData = json_encode($postData);

		$curlObj = curl_init();

		curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
		curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlObj, CURLOPT_HEADER, 0);
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
		curl_setopt($curlObj, CURLOPT_POST, 1);
		curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

		$response = curl_exec($curlObj);
		
		//change the response json string to object
		$json = json_decode($response);
		curl_close($curlObj);
		return $json->id;
		
	}
	
	//Create short tinyurl
	public static function shorttinyurl($url) 
	{ 
		$ch = curl_init(); 
		$timeout = 5; 
		curl_setopt($ch, CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);    
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		$data = curl_exec($ch); 
		curl_close($ch); 
		return $data; 
	}
}

?>