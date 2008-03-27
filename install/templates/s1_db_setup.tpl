<?php
	$siteurl = str_replace('install/install.php', '', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<form action="?page=dbsetup" method="post">
	<table width="550px">
	<tr>
	<td colspan="2">
		<p>Welcome to the phpVMS installer! Enter your database information below. You must have the following
			permissions on your database: SELECT, UPDATE, DELETE, ALTER, CREATE. For more information,
			<a href="http://www.phpvms.net/wiki/installation" target="_blank">view this page (opens in new window)</a>.</p>
		<?php 
		if($message!='')
		{
			echo '<div id="errormessage">'.$message.'</div>';
		}
		?>		
	</td>
	</tr>
	<tr>
		<td><strong>* Database Name: </strong></td>
		<td><input type="text" name="DBASE_NAME" value="<?=$_POST['DBASE_NAME']?>"/></td>	
	</tr>
	<tr>
		<td><strong>* Database Server: </strong></td>
		<td><input type="text" name="DBASE_SERVER" value="<?php echo $_POST['DBASE_SERVER']==''?'localhost':$_POST['DBASE_SERVER']; ?>"/></td>	
	</tr>
		<tr>
		<td width="1px" nowrap><strong>* Database Username: </strong></td>
		<td><input type="text" name="DBASE_USER" value="<?=$_POST['DBASE_USER']?>"/></td>
	</tr>
	<tr>
		<td><strong>* Database Password: </strong></td>
		<td><input type="text" name="DBASE_PASS" value="<?=$_POST['DBASE_PASS']?>"/></td>	
	</tr>
	<tr>
		<td><strong>* Database Type: </strong></td>
		<td>
			<select name="DBASE_TYPE">
				<option value="mysql">MySQL</option>
				<option value="mysqli">MySQLi (Extended)</option>
			</select>
		</td>	
	</tr>
	<tr>
		<td><strong>Table Prefix: </strong></td>
		<td>
		<input type="text" name="TABLE_PREFIX" 
				value="<?php echo $_POST['TABLE_PREFIX']==''?'phpvms_':$_POST['TABLE_PREFIX'];?>" />
		</td>	
	</tr>
	<tr>
		<td><strong>* Website URL: </strong></td>
		<td>
		<input type="text" name="SITE_URL" value="<?php echo $_POST['SITE_URL']==''?$siteurl:$_POST['SITE_URL'];?>" />
		</td>	
	</tr>
	<tr>
		<td><input type="hidden" name="action" value="submitdb" /></td>
		<td><input type="submit" name="submit" value="Next Step" /></td>
	</tr>
</table>
</form>