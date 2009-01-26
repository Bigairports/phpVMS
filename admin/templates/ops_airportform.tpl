<h3><?php echo $title?></h3>
<form id="form" action="<?php echo SITE_URL?>/admin/action.php/operations/airports" method="post">
<dl>
<dt>Airport ICAO Code *</dt>
<dd><input id="airporticao" name="icao" type="text" value="<?php echo $airport->icao?>" /> 
	<button id="lookupicao" onclick="lookupICAO(); return false;">Look Up</button>
</dd>

<dt></dt>
<dd><div id="statusbox"></div></dd>
<dt>Airport Name *</dt>
<dd><input id="airportname" name="name" type="text" value="<?php echo $airport->name?>" /></dd>

<dt>Country Name *</dt>
<dd><input id="airportcountry" name="country" type="text" value="<?php echo $airport->country?>"  /></dd>

<dt>Latitude *</dt>
<dd><input id="airportlat" name="lat" type="text" value="<?php echo $airport->lat?>" /></dd>

<dt>Longitude *</dt>
<dd><input id="airportlong" name="long" type="text" value="<?php echo $airport->lng?>" /></dd>

<dt>Fuel Price *</dt>
<dd><input id="fuelprice" name="fuelprice" type="text" value="<?php echo $airport->fuelprice?>" />
<p>This is the price per <?php echo Config::Get('LIQUID_UNIT_NAMES', Config::Get('LiquidUnit'))?>. Leave blank or 0 (zero) to use the default value of <?php echo Config::Get('FUEL_DEFAULT_PRICE');?>.</p>
</dd>

<dt>Hub</dt>
<?php
	if($airport->hub == '1')
		$checked = 'checked ';
	else
		$checked = '';
?>
<dd><input name="hub" type="checkbox" value="true" <?php echo $checked?>/></dd>

<dt></dt>
<dd><input type="hidden" name="action" value="<?php echo $action?>" />
	<input type="submit" name="submit" value="<?php echo $title?>" />
</dd>
</dl>
</form>