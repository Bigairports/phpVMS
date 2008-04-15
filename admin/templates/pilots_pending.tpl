<h3>Pending Pilots</h3>
<?php
if(!$allpilots)
{
	echo '<p>There are no pilots!</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Pilot Name</th>
	<th>Email Address</th>
	<th>Location</th>
	<th>Options (* double click)</th>
</tr>
</thead>
<tbody>
<?php
foreach($allpilots as $pilot)
{
?>
<tr>
	<td><?php echo $pilot->firstname . ' ' . $pilot->lastname; ?></td>
	<td align="center"><?=$pilot->email; ?></td>
	<td align="center"><?=$pilot->location; ?></td>
	<td align="center">
        <a href="action.php?admin=<?=Vars::GET('admin'); ?>" action="approvepilot"
			id="<?=$pilot->pilotid;?>" class="ajaxcall">Accept</a> * |
        <a href="action.php?admin=<?=Vars::GET('admin'); ?>" action="rejectpilot"
			id="<?=$pilot->pilotid;?>" class="ajaxcall">Reject</a> *
	</td>
</tr>
<?php
}
?>
</tbody>
</table>