<?php
/**
 * RegistrationData
 *
 * Model for any registration data
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 * 
 * @package RegistrationData
 */

class RegistrationData
{

	static public $salt;
	static public $error;
	
	/* Get the extra fields
	 */
	function GetCustomFields()
	{
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'customfields
				WHERE showonregister=\'y\'';
		
		return DB::get_results($sql);		
	}
	
	function CompleteRegistration($fields)
	{
		$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');
		$location = Vars::POST('location');
		
		//Set the password, add some salt
		$salt = md5(date('His'));
		$password = md5(Vars::POST('password1') . $salt);
		
		//Stuff it into here, the confirmation email will use it.
		self::$salt = $salt;
		
		//Add this stuff in
		
		$sql = "INSERT INTO ".TABLE_PREFIX."users (firstname, lastname, email, location, password, salt, confirmed)
					VALUES ('$firstname', '$lastname', '$email', '$location', '$password', '$salt', 'n')";
		
		$res = DB::query($sql);
		if(!$res)
		{
			if(DB::$errno == 1062)
				self::$error = 'This email address is already registered';
			else	
			{
				self::$error = 'An error occured, please contact the administrator';
				//TODO: email admin
			}
						
			return false;
		}
		
		//Grab the new userid, we need it to insert those "custom fields"
		$userid = DB::$insert_id;
		
		if(!$fields)
			return true;
			
		//Get customs fields
		foreach($fields as $field)
		{
			$value = Vars::POST($field->fieldname);
			if($value != '')
			{	
				$sql = "INSERT INTO ".TABLE_PREFIX."fieldvalues (fieldid, userid, value)
							VALUES ($field->fieldid, $userid, '$value')";
											
				DB::query();
			}
		}
	}
	
	function ChangePassword($userid, $newpassword)
	{
		$salt = md5(date('His'));
		$password = md5(Vars::POST('password1') . $salt);
		
		self::$salt = $salt;
		
		$sql = "UPDATE " . TABLE_PREFIX ."users SET password='$password', salt='$salt', confirmed='n'";
		return DB::query($sql);		
	}
	
	function SendEmailConfirm($email, $firstname, $lastname, $newpw='')
	{
		/*$firstname = Vars::POST('firstname');
		$lastname = Vars::POST('lastname');
		$email = Vars::POST('email');*/
		$confid = self::$salt;
		
		$subject = SITE_NAME . ' Registration';
		 
		//TODO: move this to a template!
		Template::Set('firstname', $firstname);
		Template::Set('lastname', $lastname);
		Template::Set('confid', $confid);
		Template::Set('newpw', $newpw);
		
		$message = Template::GetTemplate('registration_email.tpl', true);
		
		/*$message = "Dear $firstname $lastname,\nYour account have been made at " 
					. SITE_NAME .", but must confirm it by clicking on this link:\n"
					. SITE_URL . "/index.php?page=confirm&confirmid=$confid" 
					. "\n Or if you have HTML enabled email: <a href=\"" 
					. SITE_URL . "/index.php?page=confirm&confirmid=$confid" 
					. "\">Click here.</a>\n\nThanks!\n".SITE_NAME." Staff";*/

		//email them the confirmation            
		Util::SendEmail($email, $subject, $message);		
	}
	
	function ValidateConfirm()
	{
		$confid = Vars::GET('confirmid');
	
		$sql = "UPDATE ".TABLE_PREFIX."users SET confirmed='y', retired='n' WHERE salt='$confid'";
		$res = DB::query($sql);
		
		if(!$res && DB::$errno !=0)
		{
			return false;
		}
		
		return true;
	}
}

?>