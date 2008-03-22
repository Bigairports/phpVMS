<?php

class Login extends ModuleBase
{
		
	function Controller()
	{	
	
		switch(Vars::GET('page'))
		{
			case 'login':
				if(Auth::LoggedIn() == true)
				{
					echo '<p>You\'re already logged in!</p>';
					return;	
				}
				
				Template::Set('redir', Vars::GET('redir'));
				
				if(Vars::POST('action') == 'login')
				{
					$this->ProcessLogin();
				}
				else
				{
					Template::Show('login_form.tpl');
				}
				
				break;
				
			case 'logout':
			
				Auth::LogOut();
				
				/*redirect back to front page 
				*/
				Template::Set('redir', SITE_URL);
				Template::Show('login_complete.tpl');
				
				break;
			
			case 'forgotpassword':
			
				if(Vars::POST('action') == 'resetpass')
				{
					$this->ResetPassword();
					return;
				}
				
				$this->ForgotPassword();
				break;
		}	
	}

	function ForgotPassword()
	{
		Template::Show('login_forgotpassword.tpl');	
	}
	
	function ResetPassword()
	{
		$email = Vars::POST('email');
		
		if(!$email)
		{
			return false;
		}
		else
		{
			$pilotdata = PilotData::GetPilotByEmail($email);
			
			if(!$pilotdata)
			{
				Template::Show('login_notfound.tpl');
				return;
			}
			
			$newpw = substr(md5(date('mdYhs')), 0, 6);
			
			RegistrationData::ChangePassword($pilotdata->pilotid, $newpw);
						
			Template::Set('firstname', $pilotdata->firstname);
			Template::Set('lastname', $pilotdata->lastname);
			Template::Set('newpw', $newpw);
			
			$message = Template::GetTemplate('email_lostpassword.tpl', true);
			
			Util::SendEmail($pilotdata->email, 'Password Reset', $message);
			
			Template::Show('login_passwordreset.tpl');
		}		
	}
	
	function ProcessLogin()
	{
		$email = Vars::POST('email');
		$password = Vars::POST('password');
		
		if($email == '' || $password == '')
		{
			Template::Set('message', 'You must fill out both your username and password');
			Template::Show('login_form.tpl');
			return false;
		}
		
		if(!Auth::ProcessLogin($email, $password))
		{
			Template::Set('message', Auth::$error_message);
			Template::Show('login_form.tpl');
			return false;
		}
		else
		{
			//TODO: check if unconfirmed or not
			//TODO: add to sessions table 
			
			if(Auth::$userinfo->confirmed == 'n')
			{
				Auth::LogOut();
				
				// show error
			}
			else
			{
				
				Template::Set('redir', SITE_URL . '/' . Vars::POST('redir'));
				Template::Show('login_complete.tpl');
			}
			
			return;
		}
	}
	
}
?>