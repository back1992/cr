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

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');
jimport('joomla.html.parameter');

class JFormFieldDropbox extends JFormField {
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	 
	protected $type = 'Dropbox';	 	
	
	 function getInput(){	 
	
		
		$attr = '';		
			
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';		

		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';		
		
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';	
		$html ='<style>
			#uploading{
				margin-left: 140px;background:#F4F4F4;
				padding:4px 2px;
			}
			#uploading dt{
				padding:4px 72px;
			}
			#uploading dt input{
				margin-left:5px;
			}
			.btn-success {
				background-color: #5BB75B;
				background-image: linear-gradient(to bottom, #62C462, #51A351);
				background-repeat: repeat-x;
				border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
				color: #FFFFFF;
				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
				padding: 2px 14px;
			}
			.btn-remove {
				background-color: #F5F5F5;
				background-image: linear-gradient(to bottom, #FFFFFF, #E6E6E6);
				background-repeat: repeat-x;
				border-color: #BBBBBB #BBBBBB #A2A2A2;				
				border-width: 1px;
				box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
				color: #333333;
				cursor: pointer;
				display: inline-block;				
				line-height: 18px;
				margin-bottom: 0;
				padding: 2px 14px;
				text-align: center;
				text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
				vertical-align: middle;
			}
		</style>';
		$html .='<script type="text/javascript">

		function addFile(el){
			jQuery(el).parent().append(\'<a href="#" class="button" onclick="return removeFile(this)"><input class="btn btn-remove" type="button" value="Remove"></a>\');
			jQuery(el).remove();
			jQuery("#uploading").append(\'<dt><input type="text" name="'.$this->name.'[value][]'.'"><a href="#" class="button" onclick="return addFile(this)"><input class="btn btn-success" type="button" value="Add an option"></a></dt><div class="clr"></div>\');
			return false;
		}
		function removeFile(el){
			jQuery(el).parent().remove();
			return false;
		}

		</script>';		
		if(isset($this->value) && !empty($this->value)){
		
		$html .='<dl class="adminformlist" id="uploading" >';			
			$this->value= @unserialize($this->value);	
		
			$Arrayvalue = $this->value['value'];
			$count = 0;
			$html .='<dt><input type="text" name="'.$this->name.'[label]'.'" value="'.$this->value['label'].'"/> Label</dt> <div class="clr"></div>';
			if(!empty($Arrayvalue )){
				foreach ($Arrayvalue AS $key =>$value){
					$count ++;				
					$html .='<dt>';
					if($count == count($Arrayvalue)){
						$attr ='<a href="#" class="button" onclick="return addFile(this)"><input class="btn btn-success" type="button" value="Add an option"></a>';
					}
					else{
						$attr ='<a href="#" class="button" onclick="return removeFile(this)"><input class="btn btn-remove" type="button" value="Remove"></a>';
					}
					$html .='<input type="text" name="'.$this->name.'[value][]'.'" value="'.$value.'"/>'.$attr;
					$html.='</dt><div class="clr"></div>';
				}
			}else{
				$html .='<dt>			
					<input type="text" name="'.$this->name.'[value][]'.'" value=""/><a href="#" class="button" onclick="return addFile(this)"><input type="button" class="btn btn-success" value="Add an option"></a>			
					</dt>
					<div class="clr">';
			}
		
		$html .='</dl>';

		}else{	
			
		$html .='<dl class="adminformlist" id="uploading">
					<dt><input type="text" name="'.$this->name.'[label]'.'" value="--Select option--"/> Label</dt><div class="clr"></div>
					<dt>			
					<input type="text" name="'.$this->name.'[value][]'.'" value=""/><a href="#" class="button" onclick="return addFile(this)"><input type="button" class="btn btn-success" value="Add an option"></a>			
					</dt>
					<div class="clr">
				</dl>';
		}
		return $html;
	 }
	 
	
}