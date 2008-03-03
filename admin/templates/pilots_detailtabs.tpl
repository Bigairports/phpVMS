<div id="tabcontainer">
	<ul>
		<li><a href="#pilotdetails"><span>Pilot Details</span></a></li>
		<li><a href="#customfields"><span>Custom Fields</span></a></li>
		<li><a href="#pireps"><span>View PIREPs</span></a></li>
		<li><a href="#resetpass"><span>Pilot Options</span></a></li>
	</ul>
	<div id="pilotdetails">
		<?php Template::Show('pilots_details.tpl'); ?>
	</div>
	<div id="customfields">
		<?php Template::Show('pilots_customfields.tpl'); ?>
	</div>
	<div id="pireps">
		<?php Template::Show('pilots_pireps.tpl'); ?>
	</div>
	<div id="resetpass">
		<?php Template::Show('pilots_options.tpl'); ?>
	</div>
</div>
<div id="dialogresult"></div>
<div align="right"><input type="button" class="jqmClose" name="jqmClose" value="Close" /></div>