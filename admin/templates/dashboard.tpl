<h3>Administration Panel</h3>
<?php
MainController::Run('Dashboard', 'CheckInstallFolder');
MainController::Run('Dashboard', 'CheckForUpdates');
?>
<h3>Pilot Reports for the Past Week</h3>

<div id="reportcounts">Loading chart...</div>
<script type="text/javascript">
$(document).ready(function()
{
	$("#reportcounts").sparkline(<?php echo $reportcounts; ?>, {width: '90%', height: '150px'});

});
</script>

<table width="100%">
	<tr>
		<td valign="top">
			<h3>VA Stats:</h3>
			<ul>
				<li><strong>Total Pilots: </strong><?php echo StatsData::PilotCount(); ?></li>
				<li><strong>Total Flights: </strong><?php echo StatsData::TotalFlights(); ?></li>
				<li><strong>Total Hours Flown: </strong><?php echo StatsData::TotalHours(); ?></li>
			</ul>
			<h3>Site Maintenance</h3>
			<ul>
				<li><a href="?admin=resetsignatures">Reset Signatures</a></li>
			</ul>
		</td>
		<td>
			<?php echo StatsData::AircraftFlownGraph();?>
		</td>
	</tr>
</table>