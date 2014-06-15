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
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class Bt_SocialconnectControllerMessage extends JControllerForm
{

	function __construct($config = array())
	{
	
		parent::__construct($config);
	}

	
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$jinput = JFactory::getApplication()->input;
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid',(int) $jinput->getInt('filter_category_id'), 'int');
		$allow = null;		

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_bt_socialconnect.category.' . $categoryId);
		}

		if ($allow === null)
		{		
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}

	
	protected function allowEdit($data = array(), $key = 'id')
	{	
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = JFactory::getUser();
		$userId = $user->get('id');

		
		if ($user->authorise('core.edit', 'com_bt_socialconnect.message.' . $recordId))
		{
			return true;
		}
		if ($user->authorise('core.edit.own', 'com_bt_socialconnect.message.' . $recordId))
		{			
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}
				$ownerId = $record->created_by;
			}

		
			if ($ownerId == $userId)
			{
				return true;
			}
		}
		
		return parent::allowEdit($data, $key);
	}
	
	public function add()
	{
		if (!$this->allowAdd())
		{
			// Set the internal error and also the redirect error.
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item.
				'&layout=add', false));	

			return false;
		}
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item.
				'&layout=add', false));	

		return true;
	
	}
	
	public function save($key = null, $urlVar = null)
	{
	
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		// Initialise variables.
		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();
		$jinput = JFactory::getApplication()->input;
		$table = $model->getTable();
		$data  = $jinput->post->get('jform', array(), 'array');	
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();	
		if (empty($key))
		{
			$key = $table->getKeyName();
		}
		
		if (empty($urlVar))
		{
			$urlVar = $key;
		}
		$recordId = JRequest::getInt($urlVar);
		if (!$this->checkEditId($context, $recordId))
		{
			// Somehow the person just went to the form and tried to save it. We don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. '&layout=add', false
				)
			);

			return false;
		}
		$data[$key] = $recordId;
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}	
		
		// Attempt to save the data.
		if (!$model->save($data))
		{				

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. '&layout=add', false
				)
			);

			return false;
		}		

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':			
				// Set the record data in the session.
				$recordId = $model->getState($this->context . '.id');
				
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=message&layout=add', false
					)
				);
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list, false
					)
				);
				break;
		}	

		return true;
	}
	
	public function send(){			
		if (JRequest::get('post')) {
           $obLevel = ob_get_level();
			while ($obLevel > 0 ) {
				ob_end_clean();
				$obLevel --;
            }
            echo self::doPost();
            exit;
        }
		
	}
	private function doPost() {	
		$db = JFactory::getDbo();
		$result= array('success' => false, 'message' => 'Send false','senttime'=>'');
		$params = JComponentHelper::getParams('com_bt_socialconnect');
		$jinput = JFactory::getApplication()->input;
		$id	= (int)  $jinput->getInt('id', null, '', 'int');		
		$items = self::getItemsObject($id);
		//Check is publish conten
		if(!empty($items->params)){
			$available= json_decode($items->params);
			$db->setQuery($available->query);		
			$iSpublish =$db->loadResult();
		}else{
			$iSpublish =1;
		}
		//end
		$type =$items->message_type;
		$item = (object)unserialize($items->scron);
		
		$data = $item->attachment;		
		switch($type){
			case'profile':
				require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/socialpost.php');
				require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/factory.php');	
				if($iSpublish == 1){
					switch($item->alias){
						case'facebook':						
							$facebook_id = $data['item']->social_id;
							$url = "https://graph.facebook.com/$facebook_id/feed";
							$post =JHtmlBt_Socialpost::socialPost($item->alias,$data['attachment'], $url ,$item->name,$facebook_id);
							if($post['publish']==1){
								$result['success'] = TRUE;
								$result['message'] = JText::_('COM_BT_SOCIALCONNECT_SUBMIITED_SUCCESSFULL');
							}
							else{
								$result['success'] = false;
								$result['message'] = htmlspecialchars(strip_tags($post['log']));
							}
							break;
					
						case'twitter':
							if(!class_exists('TwitterOAuth')){
									require_once(JPATH_SITE . '/components/com_bt_socialconnect/helpers/html/twitter/twitteroauth.php');	
							}
							$twitter =unserialize($data['item']->access_token);						
							$connection = new TwitterOAuth($params->get('ttappId',''), $params->get('ttsecret',''), $twitter['oauth_token'], $twitter['oauth_token_secret']);
							$message =	$data['attachment'];
													
							//Share link in twitter
							$parameters = array('status' => $message);
							$status = $connection->post('statuses/update', $parameters);
								if(isset($status->errors)){
									$result['success'] = false;
									$result['message'] = $status->errors[0]->message;
									$post['log'] =$status->errors[0]->message;
									
								}else{
									$result['success'] = TRUE;
									$result['message'] = JText::_('COM_BT_SOCIALCONNECT_SUBMIITED_SUCCESSFULL');
									$post['log'] = JText::_('COM_BT_SOCIALCONNECT_SUBMIITED_SUCCESSFULL');
								}	
							break;
						
						case'linkedin':
							
							$response = JHtmlBt_Socialpost::fetch($data['item']->access_token, $data['attachment']);							
							$post = JHtmlBt_Socialpost::socialPost($item->alias,$response['body'], $response['url'] ,$item->name, $data['item']->social_id);
							if($post['publish']==1){
								$result['success'] = TRUE;
								$result['message'] = JText::_('COM_BT_SOCIALCONNECT_SUBMIITED_SUCCESSFULL');
							}
							else{
								$result['success'] = false;
								$result['message'] = htmlspecialchars(strip_tags($post['log']));
							}
							break;
					
					
					}
				}else{
					$result['success'] = false;
					$result['message'] = JText::_('COM_BT_SOCIALCONNECT_ITEM_POST_UNPUBLISH');
				}
				break;
			case'system':
				require_once(JPATH_ADMINISTRATOR.'/components/com_bt_socialconnect/publishes/btpublishes.php');
				if($iSpublish == 1){
					$name = $item->name;
					if(self::checkClass($item->alias)){
						$publishes = new BTPublishes($item->alias,$db);
						$post = $name::postMessage($publishes,$item->attachment);	
						if($post['publish']==1){
							$result['success'] = true;
							$result['message'] = JText::_('COM_BT_SOCIALCONNECT_SUBMIITED_SUCCESSFULL');
						}else{
							$result['success'] = false;
							$result['message'] = htmlspecialchars(strip_tags($post['log']));
						}
					   
					}else{
						$result['success'] = false;
						$result['message'] = JText::_('COM_BT_SOCIALCONNECT_CHANNEL_UNPUBLISH');
					}
				}
				else{
					$result['success'] = false;
					$result['message'] = JText::_('COM_BT_SOCIALCONNECT_ITEM_POST_UNPUBLISH');
				}
				break;
			
		}
		if($result['success']){
			if(!$db->connected()){
				$db = BTJFactory::createDbo();
			}
			$update_time = JFactory::getDate()->toSql();
			$result['senttime']=$update_time;
			$query='UPDATE  #__bt_messages  SET	published= \'1\',sent_time= \''.$update_time.'\',log = \''.$post['log'] .'\' WHERE id ='.(int)$id;
			$db->setQuery($query);
			$db->query();
		}
		 return json_encode($result);
	}
	
	private  function getItemsObject($id){	
		$dbo = JFactory::getDBO();
		$query = 'SELECT m.params,m.scron,m.message_type FROM #__bt_messages AS m WHERE   m.id ="'.$id.'"';		
		$dbo->setQuery($query);
		$items = $dbo->loadObject();
		return $items;
	}
	
	public static function checkClass($name){	
		$dbo = JFactory::getDBO();
		$query = 'SELECT * FROM #__bt_channels WHERE  published =1 AND alias ="'.$name.'"';		
		$dbo->setQuery($query);
		$items = $dbo->loadResult();
		return $items;
	}
	
	 public function delete() {
		$db = JFactory::getDBO();
		$query='DELETE FROM  #__bt_messages';		
		$db->setQuery($query);
		$db->query();
		$this->setRedirect(JRoute::_('index.php?option=com_bt_socialconnect&view=messages', false));
    }
	
}
