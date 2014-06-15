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

class Bt_SocialconnectController extends JControllerLegacy
{

	public function display($cachable = false, $urlparams = false)
	{
		$jinput = JFactory::getApplication()->input;
		$document	= JFactory::getDocument();
		
		$vName	 =   $jinput->get('view', 'login', 'CMD '); 	
		$vFormat = $document->getType();
		$lName	 = $jinput->get('layout', 'default', 'CMD ');  	
		if ($view = $this->getView($vName, $vFormat)) {	
	
			switch ($vName) {
			
				case 'registration':					
					$user = JFactory::getUser();					
					if ($user->get('guest') != 1) {	
							
						$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=profile', false));
						return;
					}						
            		if(JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0) { 						
						$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
						return;
            		}					
					$model = $this->getModel('Registration');
				
					break;

				
				case 'profile':
				
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {						
						$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=login', false));
						return;
					}
					$model = $this->getModel($vName);
					break;

				
				case 'login':
				
					$model = $this->getModel($vName);
					break;				

				default:
					$model = $this->getModel('Login');
					break;
			}
			
			$view->setModel($model, true);
			$view->setLayout($lName);			

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
}
