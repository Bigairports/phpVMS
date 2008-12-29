<h3>Downloads</h3>

<?php 
if(!$allcategories)
{
	echo 'There are no downloads available!';
	return;
}

foreach($allcategories as $category)
{
?>
<p><strong><?php echo $category->name?></strong></p>
<ul>

<?php	
	# This loops through every download available in the category
	$alldownloads = DownloadData::GetDownloads($category->id);
	
	if(!$alldownloads)
	{
		echo 'There are no downloads under this category';
		$alldownloads = array();
	}
	
	foreach($alldownloads as $download)
	{
?>
	<li>
		<a href="<?php echo SITE_URL;?>/index.php/downloads/<?php echo $download->id?>">
			<?php echo $download->name?>
		</a> - Downloaded <?php echo $download->hits?> times
	</li>
<?php
	}
?>
</ul>
<?php
}
?>