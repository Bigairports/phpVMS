<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/dashboard_icon.png" />Dashboard
	</a>
	<ul>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard">Site Overview</a></li>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/site_icon.png" />News & Content
	</a>
	<ul>
		<?php if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_NEWS)) 
		{ ?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewnews">News</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_PAGES)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/sitecms/viewpages">Pages</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_DOWNLOADS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/downloads/overview">Downloads</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EMAIL_PILOTS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/massmailer">Email all Pilots</a></li>
		<?php }
		?>
	</ul>
</li>
<li><a class="menu" href="?admin=airlines">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/operations_icon.png" />Airline Operations
	</a>
	 <ul>
		<?php
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_AIRLINES)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airlines">Add & Edit Airlines</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_FLEET)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/aircraft">Add & Edit Fleet</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_SCHEDULES)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/airports">Add & Edit Airports</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_SCHEDULES)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/operations/schedules">Flight Schedules & Routes</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, IMPORT_SCHEDULES)) 
			{
		?>
			<li><a href="<?php echo SITE_URL?>/admin/index.php/import">Import Schedules</a></li>
			<li><a href="<?php echo SITE_URL?>/admin/index.php/import/export">Export Schedules</a></li>
		<?php }
		?>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/pilots_icon.png" />Pilots & Groups</a>
	<ul>
		<?php
			if(PilotGroups::group_has_perm(Auth::$usergroups, MODERATE_REGISTRATIONS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pendingpilots">Pending Registrations</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_PILOTS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/viewpilots">View All Pilots</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_GROUPS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotadmin/pilotgroups">Pilot Groups</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_RANKS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotranking/pilotranks">Pilot Ranks</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EMAIL_PILOTS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/massmailer">Email all Pilots</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_AWARDS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pilotranking/awards">Awards</a></li>
		<?php }
		?>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/pireps_icon.png" />Pilot Reports (PIREPS)</a>
	<ul>
		<?php 
			if(PilotGroups::group_has_perm(Auth::$usergroups, MODERATE_PIREPS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewpending">View Pending</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, ACCESS_ADMIN)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewrecent">View Recent Reports</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, MODERATE_PIREPS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/pirepadmin/viewall">View All Reports</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_PIREPS_FIELDS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/pirepfields">PIREP Fields</a></li>
		<?php }
		?>
	</ul>
</li>
<li><a class="menu" href="<?php echo SITE_URL?>/admin/index.php/reports">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/reports_icon.png" />Reports & Expenses</a>
	<ul>
	<?php
			if(PilotGroups::group_has_perm(Auth::$usergroups, VIEW_FINANCES)) 
			{
	?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports/overview">Overview</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/finance/viewcurrent">Financial Reports</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/reports/aircraft">Aircraft Reports</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_EXPENSES)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/finance/viewexpenses">Expenses</a></li>
		<?php } ?>
	</ul>
</li>
<li><a class="menu" href="#">
		<img src="<?echo SITE_URL?>/admin/lib/layout/images/settings_icon.gif" />Site & Settings</a>
	<ul>
	<?php 
			if(PilotGroups::group_has_perm(Auth::$usergroups, ACCESS_ADMIN)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings">General Settings</a></li>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/maintenance/options">Maintenance Options</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, EDIT_PROFILE_FIELDS)) 
			{
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/settings/customfields">Profile Fields</a></li>
		<?php }
			if(PilotGroups::group_has_perm(Auth::$usergroups, ACCESS_ADMIN)) 
			{
		?>
		<?php
		if(Config::Get('PHPVMS_CENTRAL_ENABLED') == true)
			echo '<li><a href="'.SITE_URL.'/admin/index.php/vacentral">vaCentral Settings</a></li>';
		?>
		<?php }
		?>
		<li><a href="<?php echo SITE_URL?>/admin/index.php/dashboard/about">About phpVMS</a></li>
	</ul>
</li>
<?php echo $MODULE_NAV_INC;?>