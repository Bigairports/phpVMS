<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
class Auth  
{
	public static $init=false;
	public static $loggedin=false;
	public static $error_message;
	
	public static $pilotid;
	public static $userinfo;
	public static $usergroups;
	
	
	/**
	 * Start the "auth engine", see if anyone is logged in and grab their info
	 *
	 * @return mixed This is the return value description
	 *
	 */
	public function StartAuth() 
	{	
		self::$init = true;
		
		if(SessionManager::GetData('loggedin') == 'true')
		{
			self::$loggedin = true;
			self::$userinfo = SessionManager::GetData('userinfo');
			self::$usergroups = SessionManager::GetData('usergroups');
			self::$pilotid = self::$userinfo->pilotid;
			
			//if(!self::$userinfo)
				self::$userinfo = PilotData::GetPilotData(self::$pilotid);
			
			return true;
		}
		else
		{
			self::$loggedin = false;
			return false;
		}
	}
	
	
	/**
	 * Return the pilot ID of the currently logged in user
	 *
	 * @return int The pilot's ID
	 *
	 */
	public function PilotID()
	{
		return self::$userinfo->pilotid;
	}
	
	/**
	 * Get their firstname/last name
	 */
	public function DisplayName()
	{
		return self::$userinfo->firstname . ' ' . self::$userinfo->lastname;
	}
	
	/**
	 * Return true/false if they're logged in or not
	 */
	public function LoggedIn()
	{
		if(self::$init == false)
		{
			return self::StartAuth();
		}
		
		return self::$loggedin;
	}
	
	/**
	 * See if a use is in a given group
	 */
	public function UserInGroup($groupname)
	{
		if(!self::LoggedIn()) return false;
		
		if(!self::$usergroups) self::$usergroups = array();
		foreach(self::$usergroups as $group)
		{
			if($group->name == $groupname)
				return true;
		}
		
		return false;
	}
	
	/**
	 * Log the user in
	 */
	public function ProcessLogin($useridoremail, $password)
	{
		# Allow them to login in any manner:
		#  Email: blah@blah.com
		#  Pilot ID: VMA0001, VMA 001, etc
		#  Just ID: 001
		if(is_numeric($useridoremail))
		{
			$useridoremail =  $useridoremail - intval(Config::Get('PILOTID_OFFSET'));
			$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
						WHERE pilotid='.$useridoremail;
		}
		else
		{
			if(preg_match('/^.*\@.*$/i', $useridoremail) > 0)
			{
				$emailaddress = DB::escape($useridoremail);
				$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'pilots
						WHERE email=\''.$useridoremail.'\'';
			} 
			
			elseif(preg_match('/^([A-Za-z]*)(.*)(\d*)/', $useridoremail, $matches)>0)
			{
				$id = trim($matches[2]);
				$id = $id - intval(Config::Get('PILOTID_OFFSET'));
				
				$sql = 'SELECT * FROM '.TABLE_PREFIX.'pilots
							WHERE pilotid='.$id;
			}
			
			else
			{
				self::$error_message = 'Invalid user ID';
				return false;
			}
		}
	
		$password = DB::escape($password);
		$userinfo = DB::get_row($sql);

		if(!$userinfo)
		{
			self::$error_message = 'This user does not exist';
			return false;
		}

		//ok now check it
		$hash = md5($password . $userinfo->salt);
				
		if($hash == $userinfo->password)
		{	
			self::$userinfo =  $userinfo;

			SessionManager::AddData('loggedin', 'true');	
			SessionManager::AddData('userinfo', $userinfo);
			SessionManager::AddData('usergroups', PilotGroups::GetUserGroups($userinfo->pilotid));
			PilotData::UpdateLogin($userinfo->pilotid);
						
			return true;
		}			
		else 
		{
			self::$error_message = 'Invalid login, please check your username and password';
			self::LogOut();
			
			return false;
		}
	}
	
	/**
	 * Log them out
	 */	
	public function LogOut()
	{
		SessionManager::AddData('loggedin', false);
		SessionManager::AddData('userinfo', '');
		SessionManager::AddData('usergroups', '');
		
		self::$loggedin = false;
	}
}
?>