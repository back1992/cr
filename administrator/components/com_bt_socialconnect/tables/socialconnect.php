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
/**
 * Socialconnect Table class
 */
 
class Bt_SocialconnectTableSocialconnect extends JTable
{
	protected $user_fields;
	function __construct(&$db)
	{	
		$this->created_time = JFactory::getDate()->toSql();	
	
		parent::__construct('#__users', 'id', $db);		
				
		if($this->id ==0){	
			$this->user_fields = self::loadUserFields($this->user_fields);
		}
	}	

	/**
	 * Load value if iset user id
	 */
	public function load($keys = null, $reset = true){
		
		if(parent::load($keys,$reset)){	
			$id = $this->id;
			$this->password ='';
			$query = $this->_db->getQuery(true);
			$query->select('a.*');
			$query->from('#__bt_users AS a');		
			$query->where('a.user_id ='.$id);
			$this->_db->setQuery($query);			
			$row = $this->_db->loadAssoc();		
			
			$this->user_id = $row['user_id'];			
			$this->user_fields = self::loadUserFields($row);		
			
			return true;			
		
		}else{
		
			return false;
		}
		
		
	}
	/**
	 * Check type and assign value
	 */
	public static function loadUserFields($els)
	{
		$app = JFactory::getApplication();		
		if (empty($els))
		{
			$els = array();
		}
		
		$db = JFactory::getDBO();					
		$db->setQuery('SELECT * FROM #__bt_user_fields WHERE published = 1  order by ordering ');
		$r = $db->loadObjectList();
			foreach ($r as $e)
			{			
				if (array_key_exists($e->alias, $els))
				{
				
					$e->value = $els[$e->alias];
					if($e->type=='dropdown'){
					if(is_array($e->default_values)) $e->default_values = implode(',',$e->default_values);
						$e->default_values =@unserialize($e->default_values);						
					}
					
				}else{
					switch($e->type){										
						case 'dropdown':							
							if(is_array($e->default_values)) $e->default_values = implode(',',$e->default_values);
							$e->default_values =@unserialize($e->default_values);
							$e->value = '';
							break;
						default:
							$e->value = $e->default_values;
							break;
					}
				}
			}
			$newef = array();
            if($app->isSite()){
                foreach($r as $ef){
                    if(!is_array($ef->value)){   
                        if(@trim($ef->value)){
                            $newef[] = $ef;
                        }
                    }else{
                        if(@trim($ef->value[0])){
                            $newef[] = $ef;
                        }
                    }
                }
                return $newef;
            }

            return $r;
        
	}
	
}
