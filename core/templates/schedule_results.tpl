<?php
if(!$allroutes)
{
	echo '<p align="center">No routes have been found!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Flight Number</th>
	<th>Route</th>
	<th>Aircraft</th>
	<th>Departure Time</th>
	<th>Arrival Time</th>
	<th>Distance</th>
</tr>
</thead>
<tbody>
<?php
foreach($allroutes as $route)
{
	$leg = ($route->leg=='' || $route->leg == '0')?'':'Leg '.$route->leg;
?>
<tr>
	<td><?=$route->code . $route->flightnum; ?> <?=$leg?></td>
	<td align="center"><?=$route->depname?> (<?=$route->depicao; ?>) to <?=$route->arrname?> (<?=$route->arricao; ?>)</td>
	<td align="center"><?=$route->aircraft; ?></td>
	<td><?=$route->deptime;?></td>
	<td><?=$route->arrtime;?></td>
	<td><?=$route->distance;?></td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>