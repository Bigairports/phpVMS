<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/suckerfish.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<?=$MODULE_HEAD_INC;?>

<script type="text/javascript">

$(document).ready(function(){
	// The navigation, it'll apply superfish to it
	$(".nav").superfish({
		animation : { opacity:"show",height:"show"}
	});
	
	$("#code").bind('change', function()
	{
		$("#depairports").load("action.php?page=getdeptapts&code="+$(this).val());
	});
});
</script>
