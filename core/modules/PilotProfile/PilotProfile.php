<?php

class PilotProfile extends ModuleBase
{	
	function Controller()
	{	
		switch(Vars::GET('page'))
		{
			
			case 'profile':
				
				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}
				
				/* this comes from ?page=changepassword
				*/
				if($_POST['action'] == 'changepassword')
				{
					$this->ChangePassword();
				}
				
				Template::Set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
				Template::Set('report', PIREPData::GetLastReports(Auth::$userinfo->pilotid));
				Template::Set('nextrank', RanksData::GetNextRank(Auth::$userinfo->totalhours));
				Template::Set('userinfo', Auth::$userinfo);
				
				//Select aircraft types
				$stats = PIREPData::GetAircraftFlownStats(Auth::$userinfo->pilotid);
				
				$data = '';
				$labels = '';
				foreach($stats as $stat)
				{
					if($stat->aircraft == '') continue;
					
					$data .= $stat->count . ',';
					$labels .= $stat->aircraft.'|';
				}
				
				// remove that final lone char
				$data = substr($data, 0, strlen($data)-1);
				$labels = substr($labels, 0, strlen($labels)-1);
					
				$chart = new googleChart($data, 'pie');
				$chart->dimensions = '350x200';
				$chart->setLabels($labels);
			
				Template::Set('ac_chart_url', $chart->draw(false));
				
				Template::Show('profile_main.tpl');
				break;
				
			case 'editprofile':
			
				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}
				
				if($_POST['action'] == 'saveprofile')
				{
					$this->SaveProfile();
				}
											
				Template::Set('userinfo', Auth::$userinfo);
				Template::Set('customfields', PilotData::GetFieldData(Auth::$pilotid, true));
						
				Template::Show('profile_edit.tpl');
				break;
			
			case 'changepassword':
			
				if(!Auth::LoggedIn())
				{
					echo 'Not logged in';
					return;
				}
				
				Template::Show('profile_changepassword.tpl');
				break;
				
		}
	}
	
	function SaveProfile()
	{
		$userinfo = Auth::$userinfo;
		
		// save basic fields
		$email = Vars::POST('email');
		$location = Vars::POST('location');
		
		//TODO: check email validity
		if($email == '')
		{
			return;
		}
		
		PilotData::SaveProfile(Auth::$pilotid, $email, $location);
		PilotData::SaveFields(Auth::$pilotid, $_POST);
	}
	
	function ChangePassword()
	{		
		// Verify
		if($_POST['oldpassword'] == '')
		{
			Template::Set('message', 'You must enter your current password');
			Template::Show('core_message.tpl');
			return;
		}
		
		if($_POST['password1'] != $_POST['password2'])
		{
			Template::Set('message', 'Your passwords do not match');
			Template::Show('core_message.tpl');
			return;
		}
		
		// Change
		$hash = md5($_POST['oldpassword'] . Auth::$userinfo->salt);
		
		if($hash == Auth::$userinfo->password)
		{	
			RegistrationData::ChangePassword(Auth::$pilotid, $_POST['password1']);
			Template::Set('message', 'Your password has been reset');
		}
		else
		{
			Template::Set('message', 'You entered an invalid password');
		}		
		
		Template::Show('core_message.tpl');
	}
}
?>