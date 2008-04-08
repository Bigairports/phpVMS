<h3>Custom Fields</h3>
<p><a id="dialog" class="jqModal" href="action.php?admin=addfield">Add a Custom Field</a></p>
<?php
if(!$allfields)
{
	echo '<p>You have not added any custom fields</p><br />';
	return;
}
?>

<table id="tabledlist" class="tablesorter">
<thead>
	<tr>
		<th>Field Name</th>
		<th>Options</th>
	</tr>
</thead>
<tbody>
<?php
foreach($allfields as $field)
{
?>
<tr>
	<td align="center"><?=$field->title;?></td>
	<td align="center"><a href="action.php?admin=customfields" action="deletefield" id="<?=$field->fieldid;?>" class="ajaxcall">Delete</a></td>
</tr>
<?php
}
?>
</tbody>
</table>

