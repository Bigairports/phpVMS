<?php
if(GOOGLE_KEY!='') {
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=GOOGLE_KEY?>" type="text/javascript"></script>
<?php
}
?>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.sparklines.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/jquery.listen-min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/ui.tabs.min.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/ui.tabs.ext.pack.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/lib/js/phpvms.js"></script>

<?=$MODULE_HEAD_INC;?>