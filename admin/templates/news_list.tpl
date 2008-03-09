<h3>Posted News</h3>
<table id="tabledlist" class="tablesorter">
<thead>
<tr>
	<th>Subject</th>
	<th>Poster</th>
	<th>Posted Date</th>
	<th>Options</th>
</tr>
</thead>
<tbody>
<?php
foreach($allnews as $news)
{
?>
<tr>
	<td align="center"><?=$news->subject;?></td>
	<td align="center"><?=$news->postedby;?></td>
	<td align="center"><?=date(DATE_FORMAT, $news->postdate);?></td>
	<td align="center">
		<a href="action.php?admin=viewnews" action="deleteitem" id="<?=$news->id;?>" class="ajaxcall">Delete</a>
	</td>
</tr>
<?php
}
?>
</tbody>
</table>
<hr>