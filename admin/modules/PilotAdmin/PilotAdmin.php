<?php

class PilotAdmin
{
	function NavBar()
	{
		echo '<li><a href="#">Pilots</a>
				<ul>
					<li><a href="?admin=viewpilots">View Registered Pilots</a></li>
					<li><a href="?admin=pilotgroups">Pilot Groups</a></li>
				</ul>
				</li>';
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'viewpilots':

				/* This function is called for *ANYTHING* in that popup box
					
					Preset all of the template items in this function and 
					call them in the subsequent templates
					
					Confusing at first, but easier than loading each tab 
					independently via AJAX. Though may be an option later 
					on, but can certainly be done by a plugin (Add another
					tab through AJAX). The hook is available for whoever 
					wants to use it
				*/
				$action = Vars::POST('action');
				if($action == 'changepassword')
				{
					$this->ChangePassword();
					break;
				}
				
				/* These are reloaded into the #pilotgroups ID
					so the entire groups list is refreshed
					*/
				elseif($action == 'addgroup')
				{
					$this->AddPilotToGroup();
					
					$this->SetGroupsData(Vars::POST('userid'));
					Template::Show('pilots_groups.tpl');
					break;
				}
				elseif($action == 'removegroup')
				{
					$this->RemovePilotGroup();
					
					$this->SetGroupsData(Vars::POST('userid'));
					Template::Show('pilots_groups.tpl');
					break;
				}
				
				
				if(Vars::GET('action') == 'viewoptions')
				{
					
					$this->ViewPilotDetails();	
					return;
				}
				
				$this->ShowPilotsList();	
				break;	
			
			case 'pilotgroups':	
			
				if(Vars::POST('action') == 'addgroup')
				{
					$this->AddGroup();
				}
				
				$this->ShowGroups();
				break;
		}
		
	}
	
	function ShowPilotsList()
	{
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 
						 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 
						 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		Template::Set('allletters', $letters);
		Template::Set('allpilots', PilotData::GetAllPilots(Vars::GET('letter')));
		
		Template::Show('pilots_list.tpl');
	}
	
	function ViewPilotDetails()
	{
		$userid = Vars::GET('userid');
		
		//This is for the main tab
		Template::Set('pilotinfo', PilotData::GetPilotData($userid));
		Template::Set('customfields', PilotData::GetFieldData($userid, true));
		Template::Set('userid', $userid);
		
		$this->SetGroupsData($userid);
		
		Template::Show('pilots_detailtabs.tpl');
	}
	
	function SetGroupsData($userid)
	{
		//This is for the groups tab
		// Only send the groups they're in
		$freegroups = array();
		$allgroups = PilotGroups::GetAllGroups();
		foreach($allgroups as $group)
		{
			if(!PilotGroups::CheckUserInGroup($userid, $group->groupid))
			{
				array_push($freegroups, $group->name);
			}
		}
		
		Template::Set('pilotgroups', PilotData::GetPilotGroups($userid));
		Template::Set('freegroups', $freegroups);
	}
	
	function AddGroup()
	{
		$name = Vars::POST('name');
		
		if($name == '')
		{
			Template::Set('message', 'You must enter a name!');
		}
		else
		{
			if(PilotGroups::AddGroup($name))
				Template::Set('message', 'The group "'.$name.'" has been added');
			else
				Template::Set('message', 'There was an error!');
		}
		
		Template::Show('core_message.tpl');	
	}
	
	function AddPilotToGroup()
	{
		$userid = Vars::POST('userid');
		$groupname = Vars::POST('groupname');
		
		if(PilotGroups::CheckUserInGroup($userid, $groupname))
		{
			Template::Set('message', 'This user is already in this group!');
		}
		else
		{
			if(PilotGroups::AddUsertoGroup($userid, $groupname))
				Template::Set('message', 'User has been added to the group!');
			else	
				Template::Set('message', 'There was an error adding this user');
		}
		
		Template::Show('core_message.tpl');
		
	}
	
	function RemovePilotGroup()
	{
		$userid = Vars::POST('userid');
		$groupid = Vars::POST('groupid');
					
		if(PilotGroups::RemoveUserFromGroup($userid, $groupid))
		{			
			Template::Set('message', 'Removed');
		}
		else
			Template::Set('message', 'There was an error removing');
			
		Template::Show('core_message.tpl');
	}
	
	function ShowGroups()
	{
		Template::Set('allgroups', PilotGroups::GetAllGroups());
		Template::Show('groups_grouplist.tpl');	
		Template::Show('groups_addgroup.tpl');
	}
	
	function ChangePassword()
	{
		$password1 = Vars::POST('password1');
		$password2 = Vars::POST('password2');
		
		
		// Check password length
		if(strlen($password1) <= 5)
		{
			Template::Set('message', 'Password is less than 5 characters');
			Template::Show('core_message.tpl');
			return;
		}
		
		// Check is passwords are the same	
		if($password1 != $password2)
		{
			Template::Set('message', 'The passwords do not match');
			Template::Show('core_message.tpl');
			return;
		}
		
		if(RegistrationData::ChangePassword(Vars::POST('userid'), $password1))
			Template::Set('message', 'Password has been successfully changed');
		else
			Template::Set('message', 'There was an error, administrator has been notified');
			
		Template::Show('core_message.tpl');
	}
}

?>