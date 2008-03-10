<?php
/**
 * PilotGroups
 *
 * Model for pilot groups related functionality
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 * 
 * @package PilotGroups
 */

class PilotGroups
{
	function GetAllGroups()
	{
		$query = 'SELECT * FROM ' . TABLE_PREFIX .'groups 
						ORDER BY name ASC';
		
		return DB::get_results($query);
	}	
	
	function AddGroup($groupname)
	{
		$query = "INSERT INTO " . TABLE_PREFIX . "groups (name) VALUES ('$groupname')";	
		
		return DB::query($query);
	}
	
	function GetGroupID($groupname)
	{
		$query = 'SELECT groupid FROM ' . TABLE_PREFIX .'groups 
					WHERE name=\''.$groupname.'\'';
		
		$res = DB::get_row($query);
	
		return $res->groupid;
	}
	
	function AddUsertoGroup($userid, $groupidorname)
	{
		if($groupidorname == '') return false;
		
		// If group name is given, get the group ID
		if(preg_match('`^[0-9]+$`',$groupid) != true)
		{
			$groupidorname = self::GetGroupID($groupidorname);
		}
		
		$sql = 'INSERT INTO '.TABLE_PREFIX.'groupmembers (userid, groupid) 
					VALUES ('.$userid.', '.$groupidorname.')';
		
		return DB::query($sql);
	}
	
	function CheckUserInGroup($userid, $groupid)
	{
		
		if(preg_match('`^[0-9]+$`',$groupid) != true)
		{
			$groupid = self::GetGroupID($groupid);
		}
		
		$query = 'SELECT g.groupid
					FROM ' . TABLE_PREFIX . 'groupmembers g
					WHERE g.userid='.$userid.' AND g.groupid='.$groupid;
					
		if(!DB::get_row($query))
			return false;
		else	
			return true;
	}
	
	function GetUserGroups($userid)
	{
		$userid = DB::escape($userid);
		
		$sql = 'SELECT g.groupid, g.name
					FROM ' . TABLE_PREFIX . 'groupmembers u, ' . TABLE_PREFIX . 'groups g
					WHERE u.userid='.$userid.' AND g.groupid=u.groupid';
		
		$ret = DB::get_results($sql);
		
		return $ret;
	}
	
	function RemoveUserFromGroup($userid, $groupid)
	{
		$userid = DB::escape($userid);
		$groupid = DB::escape($groupid);
		
		$sql = 'DELETE FROM '.TABLE_PREFIX.'groupmembers
					WHERE userid='.$userid.' AND groupid='.$groupid;
		
		return DB::query($sql);
	}	
	
	function RemoveGroup($groupid)
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