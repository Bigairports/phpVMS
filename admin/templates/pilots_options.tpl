<h4>Reset Pilot Password</h4>
<p>If someone has forgetten their password and it needs to be reset. </p>
<form id="pilotoptionchangepass" action="action.php?admin=viewpilots" method="post">
<dl>
	<dt>Enter new password</dt>
	<dd><input type="password" name="password1" /></dd>
	
	<dt>Enter password again</dt>
	<dd><input type="password" name="password2" /></dd>
	
	<dt></dt>
	<dd><input type="hidden" name="pilotid" value="<?=Vars::GET('pilotid');?>" />
		<input type="hidden" name="action" value="changepassword" />
		<input type="submit" name="submit" value="Change Password" /></dd>
</dl>
</form>