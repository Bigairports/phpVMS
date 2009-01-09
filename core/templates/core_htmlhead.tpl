<link rel="stylesheet" media="all" type="text/css" href="<?php echo SITE_URL?>/lib/css/phpvms.css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Config::Get('PAGE_ENCODING');?>">
<?php
if(GOOGLE_KEY!='') {
echo '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.GOOGLE_KEY.'" type="text/javascript"></script>';
}
?>
<script type="text/javascript">
var urlbase = "<?php echo SITE_URL?>";
</script>
<?php
$files = array(	'jquery.min.js',
				'jquery.form.js',
				'jquery.sparklines.js',
				'jquery.listen-min.js', 
				'jquery-ui.js');
				
# Build a condensed version of the above files
#	Suck 'em all into one file, reduce the number of HTTP requests
#	May also be cached
$condenser = new CodonCondenser();
$condenser->SetOptions(SITE_ROOT.'/lib/js', SITE_URL.'/lib/js', 'js', '');
$cache_url = $condenser->GetCondensedFile($files, 'jquery-front.js');
?>
<script type="text/javascript" src="<?php echo $cache_url?>"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/phpvms.js"></script>

<?php echo $MODULE_HEAD_INC;?>