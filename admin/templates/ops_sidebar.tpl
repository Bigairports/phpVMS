<h3>Tasks</h3>

<ul class="filetree treeview-famfamfam">

<?php
if($_GET['admin'] == 'airports')
{
?>
	<li><span class="file">
	<a id="dialog" class="jqModal" href="action.php?admin=addairport">Add a new Airport</a>
	</span></li>
	
	</ul>
	<h3>Help</h3>
	<p>Add the airports that your VA operates from here. 
		Entering the latitude and longitude will allow it to be placed on Google Maps. 
		You will also need to enter airports before you create schedules with them.</p>
<?php
}
elseif($_GET['admin'] == 'aircraft')
{
?>
 <li><span class="file">
	<a id="dialog" class="jqModal" href="action.php?admin=addaircraft">Add an aircraft</a>
	</span></li>
	</ul>
<h3>Help</h3>
<p>Add the aircraft that your VA operates from here. The aircraft name 
	is what is displayed in schedules. The ICAO and the full name are used for reference.</p>
<?php
}
?>