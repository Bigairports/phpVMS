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
 
class PilotGroups
{
	/**
	 * Get all of the groups
	 */
	public static function GetAllGroups()
	{
		$query = 'SELECT * 
					FROM ' . TABLE_PREFIX .'groups
					ORDER BY name ASC';
		
		return DB::get_results($query);
	}
	
	/**
	 * Add a group
	 */
	public static function AddGroup($groupname, $permissions)
	{
		$query = "INSERT INTO " . TABLE_PREFIX . "groups 
					(`name`, `permissions`) VALUES ('$groupname', $permissions)";
		
		$res = DB::query($query);
				
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function EditGroup($groupid, $groupname, $permissions)
	{
		$groupid = intval($groupid);
		$groupname = DB::escape($groupname);
		
		$query = 'UPDATE '.TABLE_PREFIX."groups
					SET `name`='$groupname', `permissions`=$permissions
					WHERE `groupid`=$groupid";
					
		$res = DB::query($query);
		
		if(DB::errno() != 0)
			return false;
		
		return true;
	}
	
	public static function GetGroup($groupid)
	{
		$groupid = intval($groupid);
		
		$query = 'SELECT *
					FROM ' . TABLE_PREFIX .'groups
					WHERE groupid='.$groupid;
		
		return DB::get_row($query);
	}
	
	/**
	 * Get a group ID, given the name
	 */
	public static function GetGroupID($groupname)
	{
		$query = 'SELECT groupid FROM ' . TABLE_PREFIX .'groups
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
	
		return $res->groupid;
	}
	
	/**
	 * Add a user to a group, either supply the group ID or the name
	 */
	public static function AddUsertoGroup($pilotid, $groupidorname)
	{
		if($groupidorname == '') return false;
		
		// If group name is given, get the group ID
		if(!is_numeric($groupidorname))
		{
			$groupidorname = self::GetGroupID($groupidorname);
		}
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'groupmembers (pilotid, groupid)
					VALUES ('.$pilotid.', '.$groupidorname.')';
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	public static function group_has_perm($grouplist, $perm)
	{
		foreach($grouplist as $group)
		{
			# Check zero (NO_ADMIN_ACCESS === 0)
			if($group->permissions === NO_ADMIN_ACCESS)
				continue;
			
			# One of the group has full admin access
			if($group->permissions === FULL_ADMIN)
			{
				return true;
			}
			
			# Check individually
			if(self::check_permission($group->permissions, $perm))
				return true;
		}
		
		return false;
	}
	
	/**
	 * Check permissions against integer set 
	 * (bit compare, ($set & $perm) === $perm)
	 *
	 * @param int $set Permission set &
	 * @param int $perm Permission (intval)
	 * @return bool Whether it's set or not
	 *
	 */
	public static function check_permission($set, $perm)
	{
		if(($set & $perm) === $perm)
		{
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Set a permission ($set | $permission)
	 *
	 * @param int $set Integer set
	 * @param int $perm Permission to remove
	 * @return int New permission set
	 *
	 */
	public static function set_permission($set, $perm)
	{
		return $set | $perm;
	}
	
	
	/**
	 * Remove permission from set ($set ^ $perm)
	 *
	 * @param int $set Permission set
	 * @param int $perm Permission to remove
	 * @return int New permission set
	 *
	 */
	public static function remove_permission($set, $perm)
	{
		$set = $set ^ $perm;		
	}
	
	/**
	 * Check if a user is in a group, pass the name or the id
	 */
	public static function CheckUserInGroup($pilotid, $groupid)
	{
		
		if(!is_numeric($groupidorname))
		{
			$groupid = self::GetGroupID($groupid);
		}
		
		$query = 'SELECT g.groupid
					FROM ' . TABLE_PREFIX . 'groupmembers g
					WHERE g.pilotid='.$pilotid.' AND g.groupid='.$groupid;
					
		if(!DB::get_row($query))
			return false;
		else
			return true;
	}
	
	/**
	 * The a users groups (pass their database ID)
	 */
	public static function GetUserGroups($pilotid)
	{
		$pilotid = DB::escape($pilotid);
		
		$sql = 'SELECT g.*
				FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
				WHERE u.pilotid='.$pilotid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;
	}
	
	/**
	 * Remove a user from a group (pass the ID or the name)
	 */
	public static function RemoveUserFromGroup($pilotid, $groupid)
	{
		$pilotid = DB::escape($pilotid);
		$groupid = DB::escape($groupid);
		
		if(!is_numeric($groupid))
		{
			$groupid = self::GetGroupID($groupid);
		}
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers
					WHERE pilotid='.$pilotid.' AND groupid='.$groupid;
		
		$res = DB::query($sql);
		
		if(DB::errno() != 0)
			return false;
			
		return true;
	}
	
	/**
	 * Remove a group
	 */
	public static function RemoveGroup($groupid)
	{
		$groupid = DB::escape($groupid);
		
		//delete from groups table
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groups WHERE groupid='.$groupid;
		DB::query($sql);
				
		//delete from usergroups table
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers WHERE groupid='.$groupid;
		DB::query($sql);
	}
}
?>