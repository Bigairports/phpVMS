<?php

include dirname(__FILE__) . '/RegistrationData.class.php';

class Registration extends ModuleBase
{
	function HTMLHead()
	{
		/*Show our password strength checker
			*/
		if(Vars::GET('page') == 'register')
		{
			Template::ShowTemplate('registration_javascript.tpl');
		}
	}
	
	function NavBar()
	{ 
		if(!Auth::LoggedIn())
			echo '<li><a href="?page=register">Register</a></li>';
	}
	
	function Controller()
	{	
	
		if(Vars::GET('page') == 'confirm')
		{
			if(RegistrationData::ValidateConfirm())
			{
				Template::Show('registration_complete.tpl');
			}
			else
			{
				//TODO: error template, notify admin
				DB::debug();
			}
		}
		
		if(Vars::GET('page') == 'register')
		{			
			if(Auth::LoggedIn()) // Make sure they don't over-ride it
				return;
	
			//Get the extra fields, that'll show in the main form
			$extrafields = RegistrationData::GetCustomFields();
			Template::Set('extrafields', $extrafields);
			
			if(isset($_POST['submit_register']))
			{
				// check the registration
				$ret = RegistrationData::ProcessRegistration();
				
				// Yes, there was an error
				if($ret == false) 
				{
					Template::Show('registration_mainform.tpl');
				}
				else
				{
					if(RegistrationData::CompleteRegistration($extrafields) == false)
					{
						//TODO: notify admin
						Template::Show('registration_error.tpl');
					}
					else
					{
						RegistrationData::SendEmailConfirm();
						Template::Show('registration_sentconfirmation.tpl');
					}
				}
			}
			else
			{				
				Template::Show('registration_mainform.tpl');
			}
		}
	}
	
}
?>