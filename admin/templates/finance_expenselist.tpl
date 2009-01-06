<h3>Current Expenses</h3>
<?php
if(!$allexpenses)
{
	echo '<p>No expenses have been added</p>';
	return;
}
?>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Name</th>
	<th>Price</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allexpenses as $expense)
{
?>
<tr>
	<td align="center"><?php echo $expense->name; ?></td>
	<td align="center"><?php echo Config::Get('MONEY_UNIT').$expense->cost; ?>/mo</td>
	<td align="center" width="1%" nowrap>
		<a id="dialog" class="jqModal" 
			href="<?php echo SITE_URL?>/admin/action.php/finance/editexpense?id=<?php echo $expense->id;?>">
		<img src="<?php echo SITE_URL?>/admin/lib/images/edit.png" alt="Edit" /></a>
		
		<a href="<?php echo SITE_URL?>/admin/action.php/finance/viewexpenses" action="deleteexpense"
			id="<?php echo $expense->pirepid;?>" class="ajaxcall">
			<img src="<?php echo SITE_URL?>/admin/lib/images/delete.png" alt="Delete" /></a>
	</td>
</tr>
	<?php
}
?>
</tbody>
</table>