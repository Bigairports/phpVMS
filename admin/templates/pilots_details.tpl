<form id="dialogform" action="action.php?admin=viewpilots" method="post">
<dl> 
	<dt>Email Address</dt>
	<dd><input type="text" name="email" value="<?=$pilotinfo->email;?>" /></dd>

	<dt>Airline</dt>
	
	
	<dd>
	<select name="code">
	<?php
	$allairlines = OperationsData::GetAllAirlines();
	foreach($allairlines as $airline)
	{
		if($pilotinfo->code == $airline->code)
			$sel =  ' selected';
		else
			$sel = '';
			
		echo '<option value="'.$airline->code.'" '.$sel.'>'.$airline->name.'</option>';
	}
	?>	
	</select>
	</dd>
	
	<dt>Location</dt>
	<dd><input type="text" name="location" value="<?=$pilotinfo->location==''?'-':$pilotinfo->location;?>" /></dd>
	
	<dt>Hub</dt>
	<dd>
	<select name="hub">
	<?php
	$allhubs = OperationsData::GetAllHubs();
	foreach($allhubs as $hub)
	{
		if($pilotinfo->hub == $hub->icao)
			$sel = ' selected';
		else
			$sel = '';
		
		echo '<option value="'.$hub->icao.'" '.$sel.'>'.$hub->icao.' - ' . $hub->name .'</option>';
	}
	?>	
	</select>
	</dd>
	
	<dt>Current Rank</dt>
	<dd><?=$pilotinfo->rank;?></dd>

	<dt>Last Login</dt>
	<dd><?php echo date(DATE_FORMAT, $pilotinfo->lastlogin);?></dd>

	<dt>Total Flights</dt>
	<dd><input type="text" name="totalflights" value="<?=$pilotinfo->totalflights;?>" /></dd>

	<dt>Total Hours</dt>
	<dd><input type="text" name="totalhours" value="<?=$pilotinfo->totalhours;?>" /></dd>
	
	<dt>Total Pay</dt>
	<dd><input type="text" name="totalpay" value="<?=$pilotinfo->totalpay;?>" /></dd>
	
<?php
if($customfields)
{
	foreach($customfields as $field)
	{
?>
	<dt><?=$field->title;?></dt>
	<dd><input type="text" name="<?=$field->fieldname?>" value="<?=$field->value?>" /></dd>
<?php
	}
}
?>	
	<dt></dt>
	<dd>
		<input type="hidden" name="pilotid" value="<?=$pilotinfo->pilotid;?>" />
		<input type="hidden" name="action" value="saveprofile" />
		<input type="submit" name="submit" value="Save Changes" />
		<div id="results"></div>
	</dd>
</dl>
</form>