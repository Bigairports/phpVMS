<h3>Airlines List</h3>
<p>These are the airlines that belong to your aircraft group.</p>
<p><a id="dialog" class="jqModal" href="action.php?admin=addairline"><img src="lib/images/addairline.gif" alt="Add Airline" /></a></p>
<?php
if(!$allairlines)
{
	echo '<p id="error">No airlines have been added</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Code</th>
	<th>Name</th>	
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allairlines as $airline)
{
?>
<tr>
	<td align="center"><?=$airline->code; ?></td>
	<td align="center"><?=$airline->name; ?></td>
	<td align="center"><a id="dialog" class="jqModal" href="action.php?admin=editairline&id=<?=$airline->id;?>"><img src="lib/images/options.gif" alt="Options" /></a></td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>