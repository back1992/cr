<?php
/**
 * @package 	bt_socialconnect_user - BT Social Connect User
 * @version		1.0.0
 * @created		January 2014
 * @author		BowThemes
 * @email		support@bowthems.com
 * @website		http://bowthemes.com
 * @support		Forum - http://bowthemes.com/forum/
 * @copyright	Copyright (C) 2014 Bowthemes. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.helper');
class plgUserBt_socialconnect_user extends JPlugin
{		
	function onUserAfterDelete($user, $success, $msg)
    {
                if (!$success) {
                        return false;
                }
 
                $userId = JArrayHelper::getValue($user, 'id', 0, 'int');
				$Obalias =self::getValuealias();
				$Oldata = self::getOldata($userId);
 
                if ($userId)
                {
                        try
                        {                           
							foreach ($Oldata as $img)
							{			
								foreach($Obalias AS $key=>$alias){
									$value = $alias->alias;
									$ImgFormats = array("png", "jpg", "jpeg", "gif", "tiff");//Etc. . . 
									$PathInfo = pathinfo($img->$value);
									  if (@in_array(@strtolower($PathInfo['extension']), $ImgFormats)) {
										@unlink(JPATH_SITE.'/'.$img->$value);
									}
								}
							}
							$db = JFactory::getDbo();
                            $db->setQuery(
                                'DELETE FROM #__bt_users WHERE user_id = '.$userId                                   
                            );
							if ($db->query()){
								$db = JFactory::getDbo();
								$db->setQuery(
									'DELETE FROM #__bt_connections WHERE user_id = '.$userId                                   
								);	 
								if (!$db->query()) {
									throw new Exception($db->getErrorMsg());
								}
							}
                        }
                        catch (JException $e)
                        {
                                $this->_subject->setError($e->getMessage());
                                return false;
                        }
                }
 
                return true;
    }	
	
	public static function getValuealias(){	
			
		$db	 = JFactory::getDBO();
		$query ='SELECT a.alias FROM #__bt_user_fields AS a WHERE a.published = 1 ' ;
		$db->setQuery( $query );			
		$row = $db->loadObjectlist();	
	 
		return $row;
	}
	
	static function getOldata($id){		
		$db = JFactory::getDbo();	
		$db->setQuery('Select * from #__bt_users WHERE user_id > 0 and user_id = ' .(int)$id . '');
		return $db->loadObjectList();
	}
	
	
}

?>