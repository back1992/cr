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


jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {

    protected $type = 'Asset';

    protected function getInput() { 
		JLoader::register('Bt_SocialconnectLegacyHelper', JPATH_ADMINISTRATOR . '/components/com_bt_socialconnect/helpers/legacy.php');
        JHTML::_('behavior.framework');
        $checkJqueryLoaded = false;
        $document = JFactory::getDocument();       
		
        if (!version_compare(JVERSION, '3.0', 'ge')) {		
            $document->addScript(JURI::root() . 'components/com_bt_socialconnect/assets/js/jquery.min.js');
            $document->addScript(JURI::root() . $this->element['path'] . 'js/chosen.jquery.min.js');
			$document->addStyleSheet(JURI::root() . $this->element['path'] . 'css/chosen.css');
        }		
        $document->addScript(JURI::root() . $this->element['path'] . 'js/colorpicker/colorpicker.js');
        $document->addScript(JURI::root() . $this->element['path'] . 'js/bt.js');      


        //Add css
        $document->addStyleSheet(JURI::root() . $this->element['path'] . 'css/bt.css');
        $document->addStyleSheet(JURI::root() . $this->element['path'] . 'js/colorpicker/colorpicker.css');
        

        return null;
    }

}