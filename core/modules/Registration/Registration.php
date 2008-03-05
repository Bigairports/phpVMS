<?php

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
		
	function Controller()
	{	
	
		/* Verify the confirmation code from the email
		 */
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
		
		/* Show the registration page
		 */
		if(Vars::GET('page') == 'register')
		{			
			if(Auth::LoggedIn()) // Make sure they don't over-ride it
				return;
	
			//Get the extra fields, that'll show in the main form
			// Keep them in a var, so we don't have to do the query again
			// when completing the registration
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
						Template::Set('error', RegistrationData::$error);
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