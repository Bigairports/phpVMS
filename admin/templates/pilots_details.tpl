<form id="dialogform" action="action.php?admin=viewpilots" method="post">
<dl> 
	<dt>Email Address</dt>
	<dd><?=$pilotinfo->email;?></dd>

	<dt>Airline</dt>
	<dd><?=$pilotinfo->code;?></dd>
	
	<dt>Location</dt>
	<dd><?=$pilotinfo->location==''?'-':$pilotinfo->location;?></dd>
	
	<dt>Hub</dt>
	<dd><?=$pilotinfo->hub?></dd>
	
	<dt>Current Rank</dt>
	<dd><?=$pilotinfo->rank;?></dd>

	<dt>Last Login</dt>
	<dd><?php echo date(DATE_FORMAT, $pilotinfo->lastlogin);?></dd>

	<dt>Total Flights</dt>
	<dd><?=$pilotinfo->totalflights;?></dd>

	<dt>Total Hours</dt>
	<dd><?=$pilotinfo->totalhours;?></dd>
	
<?php
if($customfields)
{
	foreach($customfields as $field)
	{
?>
	<dt><?=$field->title;?></dt>
	<dd><?=$field->value==''?'-':$field->value;?></dd>
<?php
	}
}
?>	
	<dt></dt>
	<dd>
		<div id="results"></div>
	</dd>
</dl>
</form>