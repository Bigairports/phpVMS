<h3>Pilot Ranks</h3>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Rank Title</th>
	<th>Minimum Hours</th>
	<th>Rank Image</th>
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
<tr id="row<?php echo $rank->rankid;?>">
	<td align="center"><?php echo $rank->rank; ?></td>
	<td align="center"><?php echo $rank->minhours; ?></td>
	<td align="center"><img src="<?php echo $rank->rankimage; ?>" /></td>
	<td align="center"><?php echo Config::Get('MONEY_UNIT').$rank->payrate.'/hr'; ?></td>
	<td align="center"><?php echo $rank->totalpilots; ?></td>
	<td align="center" width="1%" nowrap>
		<a id="dialog" class="jqModal" 
			href="<?php echo SITE_URL?>/admin/action.php/pilotranking/editrank?rankid=<?php echo $rank->rankid;?>">
			<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
		<a href="<?php echo SITE_URL?>/admin/action.php/pilotranking/pilotranks" action="deleterank" 
			id="<?php echo $rank->rankid;?>" class="deleteitem">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a></td>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>