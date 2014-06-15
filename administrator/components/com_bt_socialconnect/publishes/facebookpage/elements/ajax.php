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
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('list');

class JFormFieldAjax extends JFormFieldList {

	protected $type = 'ajax';
	private $result = array();	
	
	function getInput()
	{		
		if (JRequest::get('post')) {
           $obLevel = ob_get_level();
			if($obLevel){
				while ($obLevel > 0 ) {
					ob_end_clean();
					$obLevel --;
				}
			}else{
				ob_clean();
			}
            echo self::doPost();
            exit;
        }
		
	}
	
	public static function curlResponse($url,$data = null){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
			if($data){
				curl_setopt($ch,CURLOPT_POST, true);
				curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
			}
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
	}
		
	public function doPost() {	
		
		$client_id =JRequest::getString('client_id');
		
		$appfb_secret =JRequest::getString('client_secret');
		$callback_url =JRequest::getString('redirect_uri');
		
		$code =JRequest::getString('code');
		$method =JRequest::getString('method');
		
		if($method =='gettoken'){
			$token_url= "https://graph.facebook.com/oauth/access_token?"
			   . "client_id=" . $client_id . "&redirect_uri=" . urlencode($callback_url)
			   . "&client_secret=" . $appfb_secret ."&code=" . $code;
			   
			$response = self::curlResponse($token_url);
			$paramsFB = null;
			parse_str($response, $paramsFB);	
			$this->result['access_token']=$paramsFB['access_token'];
			return json_encode ($this->result);
		}		
		if($method =='getuser'){
			$token =JRequest::getString('access_token');
			//Get info user
			$graph_url = "https://graph.facebook.com/me?access_token=".$token;
			$user = self::curlResponse($graph_url);	
			$user =json_decode($user);
			//Get info fanpage
			$page_url = 'https://graph.facebook.com/me/accounts?access_token='.$token;
			$page = self::curlResponse($page_url);
			$page =json_decode($page);			
			$this->result['uid']=$user->id;
			$this->result['uname']=$user->name;
			$this->result['data']=self::prepareData($user,$page);
			return json_encode ($this->result);			
		}
		
	}
	public function prepareData($user,$page_manager){
		
		$html ='';			
		$html .='<div style="height:50px;"><a href='.$user->link.' target="_blank"><img style="width:50px; height:50px;float:left;margin:5px" src="http://graph.facebook.com/'.$user->id.'/picture"></a><br/>';
		$html .='<a href='.$user->link.' target="_blank" >'.$user->name.'</a></div><br/>';
		foreach($page_manager AS $key =>$pages){			
			foreach($pages AS $key =>$page){
				if(isset($page->id)&& $page->id!=''){
					$html .= '<input type="checkbox" value="'.$page->id.'" name="group['.$key.'][checked]"/> ';
					$html .= '<a href="https://www.facebook.com/'.$page->id.'" target="_blank">'.$page->name.'</a>';
					$html .='<input type="hidden" value="'.$page->id.'"" name="group['.$key.'][id]"/><br/>';
					$html .='<input type="hidden" value="'.$page->name.'" name="group['.$key.'][name]"/><br/>';
					$html .='<input type="hidden" value="'.$page->access_token.'" name="group['.$key.'][access_token]"/>';
				}
			}
		}
		
		return $html;
	}
}