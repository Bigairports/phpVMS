<h3>Add to Group</h3>
<form id="dialogform" action="action.php?admin=viewpilots" method="post">

<dl>
	<dt>Select Group:</dt>
	<dd><select name="groupname">
		<?php
			$total = count($freegroups);
			for($i=0;$i<$total;$i++)
			{
				echo '<option value="'.$freegroups[$i].'">'.$freegroups[$i].'</option>';
			}
		?>
		</select></dd>

	<dt></dt>
	<dd><input type="hidden" name="userid" value="<?=Vars::GET('userid');?>" />
		<input type="hidden" name="action" value="addgroup" />
		<input type="submit" name="submit" value="Add to Group" /></dd>
</dl>
</form>