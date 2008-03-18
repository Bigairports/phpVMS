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
					echo 'You\'re already logged in!';
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
			
			case 'forgotpassword':
			
				if(Vars::POST('action') == 'resetpass')
				{
					$this->ResetPassword();
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
			//TODO: reset the password
		}		
		
		return true;	
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
			
			Template::Set('redir', SITE_URL . '/' . Vars::POST('redir'));
			Template::Show('login_complete.tpl');
			return;
		}
	}
	
}
?>