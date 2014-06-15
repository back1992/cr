<?php
/**
 * @package 	bt_socialconnect_button - BT Social Connect Button
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


class plgButtonBt_widget_button extends JPlugin
{

	protected $autoloadLanguage = true;

	
	public function onDisplay($name)
	{
		 $user = JFactory::getUser();
		$js = "	function jSelectWidget(alias) {			
			var tag ='{'+'widget:'+alias+'}'; 				
			jInsertEditorText(tag, '".$name."');
			SqueezeBox.close();		
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		JHtml::_('behavior.modal');

		
		$link = 'index.php?option=com_bt_socialconnect&amp;view=widgets&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

		$button = new JObject;
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text = JText::_('PLG_BT_SOCIALCONNECT_BUTTON_TEXT');
		$button->name = 'file-add';
		$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

		return $button;
	}
}
