<h3>Pilot Ranks</h3>
<p>You can define your pilot ranks here. When a PIREP is accepted, the pilot is automatically placed into the proper ranking.</p>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Rank Title</th>
	<th>Minimum Hours</th>
	<th>Pay Rate</th>
	<th>Total Pilots</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($ranks as $rank)
{
?>
<tr>
	<td align="center"><?php echo $rank->rank; ?></td>
	<td align="center"><?php echo $rank->minhours; ?></td>
	<td align="center"><?php echo $rank->payrate; ?></td>
	<td align="center"><?php echo $rank->totalpilots; ?></td>
	<td align="center">
		<a id="dialog" class="jqModal" href="action.php?admin=editrank&rankid=<?php echo $rank->rankid;?>"><img src="lib/images/edit.gif" alt="Edit" /></a>
		
		<a href="action.php?admin=pilotranks" action="deleterank" id="<?php echo $rank->rankid;?>" class="ajaxcall">
			<img src="lib/images/delete.gif" alt="Delete" /></a></td>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>