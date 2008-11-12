<?php
if($title!='')
	echo "<h3>$title</h3>";
?>
<p><?=$descrip;?></p>
<?php
if(!$pireps)
{
	echo '<p>No reports have been found</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>PIREP Information</th>
	<th>Details</th>
	<th>Options (* for double click)</th>
</tr>
</thead>
<tbody>
<?php
foreach($pireps as $report)
{
	if($report->accepted == PIREP_ACCEPTED)
		$class = 'success';
	else
		$class = 'error';
?>

<tr class="<?=$class?>">
	<td align="left" valign="top" width="10%" nowrap>
		<a href="?admin=viewpilots&action=viewoptions&pilotid=<?=$report->pilotid;?>"><?=$report->firstname .' ' . $report->lastname?></a><br />
		<strong>Flight: <?=$report->code . $report->flightnum; ?></strong> - 
					<?=date(DATE_FORMAT, $report->submitdate); ?><br />
		Dep/Arr: <?=$report->depicao; ?>/<?=$report->arricao; ?><br />
		Flight Time: <?=$report->flighttime; ?><br />
		<strong>Current Status:	</strong>
			<?php 
			
			if($report->accepted == PIREP_ACCEPTED)
				echo 'Accepted';
			elseif($report->accepted == PIREP_REJECTED)
				echo 'Rejected';
			elseif($report->accepted == PIREP_PENDING)
				echo 'Approval Pending';
			elseif($report->accepted == PIREP_INPROGRESS)
				echo 'In Progress';
			
			?><br />
		<?php
		if($report->log != '')
		{
		?>
			<a id="dialog" class="jqModal"
				href="action.php?admin=viewlog&pirepid=<?=$report->pirepid;?>">View Log Details</a>
		<?php
		}
		?>
	</td>
	<td align="left" valign="top" >
	<?php
		// Get the additional fields
		//	I know, badish place to put it, but it's pulled per-PIREP
		$fields = PIREPData::GetFieldData($report->pirepid);
		
		if(!$fields)
		{
			echo 'No additional data found';
		}
		else
		{
			foreach ($fields as $field)
			{
		?>		<strong><?=$field->title ?>:</strong> <?=$field->value ?><br />
		<?php
			}
		}
		?>
	</td>
	<td align="center" width="10%" nowrap>
		<a id="dialog" class="jqModal"
			href="action.php?admin=viewcomments&pirepid=<?=$report->pirepid;?>">View Comments</a>
		<br />
		<a href="action.php?admin=<?=Vars::GET('admin'); ?>" action="approvepirep"
			id="<?=$report->pirepid;?>" class="ajaxcall"><img src="lib/images/accept.gif" alt="Accept" /></a>
		<br />
		<a id="dialog" class="jqModal"
			href="action.php?admin=rejectpirep&pirepid=<?=$report->pirepid;?>"><img src="lib/images/reject.gif" alt="Reject" /></a>
		<br />
		<a id="dialog" class="jqModal"
			href="action.php?admin=addcomment&pirepid=<?=$report->pirepid;?>"><img src="lib/images/addcomment.gif" alt="Add Comment" /></a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>

<?php
if($paginate)
{
?>
<a href="?admin=<?=$admin?>&start=<?=$start?>">Next Page</a></a>
<?php
}
?>