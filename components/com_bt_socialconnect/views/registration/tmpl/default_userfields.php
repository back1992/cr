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
 
// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
jimport( 'joomla.html.editor' );
$params = $this->form->getFieldsets('params');

?>
<?php

if (count($this->data->user_fields))
{

	foreach ($this->data->user_fields as $key => $el)
	{	
		if($el->required){		
		
			$title =' class="hasTip required" for="user_fields_'.$el->alias.'"';
			$required ='class="validate-'.$el->alias.' required"  id="user_fields_'.$el->alias.'" aria-required="true" required="required"';
			$span ='<span class="star"> *</span>';
		}
		else{
			$title  ='';
			$required ='class="inputbox"';
			$span ='';
		}
		
?>
<div class="control-group">
<dt><div class="control-label"><label title="<?php echo strip_tags($el->description) ;?>" <?php echo $title;?>><?php echo Jtext::_($el->name); ?> <?php echo $span ;?></label></div></dt>
<dd><div class="controls btl-input"> 	
											<?php switch ($el->type)
													 {
														 case 'date':
															if($el->value =='' || $el->value =='0000-00-00'){
																$el->value= null;
															}
															 echo JHTML::_('calendar', $el->value , 'user_fields[' . $el->alias . ']', 'user_fields_'.$el->alias, '%Y-%m-%d ', $required);
															 break;
														 case 'string':														
															 echo '<input size="35" '.$required.' type="text" name="user_fields[' . $el->alias . ']" value="' . $el->value . '">';
															
															break;
														 case 'text':
															 $wysiwyg = JEditor::getInstance();															 
															 echo $wysiwyg->display('user_fields[' . $el->alias . ']', strip_tags($el->value), '365', '140', '75', '20', false );
															 break;													
														case'dropdown':													
															$options = array();															
															$options[] = JHtml::_('select.option', '',$el->default_values['label']);
															foreach ($el->default_values['value'] as $value){
																$options[] = JHtml::_('select.option', $value,$value);
															}	
															echo JHtml::_('select.genericlist',$options,'user_fields[' . $el->alias . '][]',$required,'value','text',$el->value);
																														
															break;
														case'image':	
															
															if($el->value!=''){	
																
																$avatar='<img src="'.JURI::root().'images/bt_socialconnect/avatar/'.$el->value.'"/>';				
																$html ='<div class=\'imageupload\'>';				
																	$html .='<span class="editlinktip hasTip" title="'. htmlspecialchars($avatar).'">';
																	$html .='<img src='. JURI::root().'images/bt_socialconnect/avatar/'.$el->value.' width=\'50\' />';	
																	$html .='<input type="hidden" name="user_fields[' . $el->alias . ']" value="'.$el->value.'" />';	
																	$html .='</span>';
																	$html .= '<span"><input type="file" name="user_fields[' . $el->alias . ']" style="width:202px"  class="inputbox" size="14"/></span>';			
																$html .='</div>';
																$html .='<input type="checkbox" class="textbook" name="user_fields[' . $el->alias . ']" value="">'.Jtext::_('COM_BT_SOCIALCONNECT_DELETE_IMAGE');
															}
															else{
																$html ='<div class=\'inputupload\'>';
																$html .= '<input type="file" name="user_fields[' . $el->alias . ']"  '.$required.' size="30"/>';
																$html .='</div>';
															}
															 echo $html;
															 break;
													 }
											   ?>
</div></dd>
</div>
<?php

	}
}

?>
