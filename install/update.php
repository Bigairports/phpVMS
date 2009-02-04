<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Nabeel Shahzad
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.phpvms.net
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
 
define('ADMIN_PANEL', true);

include '../core/codon.config.php';
include dirname(__FILE__).'/Installer.class.php';
 
# phpVMS Updater 
define('INSTALLER_VERSION', '1.2.<<REVISION>>');
define('UPDATE_VERSION', '1.2.<<REVISION>>');
define('REVISION', '<<REVISION>>');

Template::SetTemplatePath(SITE_ROOT.'/install/templates');
Template::Show('header.tpl');

# Ew
echo '<h3 align="left">phpVMS Updater</h3>';


# Check versions for mismatch, unless ?force is passed
if(!isset($_GET['force']) && !isset($_GET['test']))
{
	if(PHPVMS_VERSION == UPDATE_VERSION)
	{
		echo '<p>You already have updated! Please delete this /install folder.<br /><br />
				To force the update to run again, click: <a href="update.php?force">update.php?force</a></p>';
		
		Template::Show('footer.tpl');
		exit;
	}
}

/** 
 * Run a sql file
 */
// Do the queries:
echo 'Starting the update...<br />';


	# Do updates based on version
	#	But cascade the updates

	$version = intval(str_replace('.', '', PHPVMS_VERSION));
	
	if($version  < 11400)
	{
		Installer::sql_file_update(SITE_ROOT . '/install/update_400.sql');
		Installer::add_to_config('UNITS', 'mi');
	}
	elseif($version <  11428)
	{
		Installer::sql_file_update(SITE_ROOT . '/install/update_437.sql');
		
		echo 'Adding some options to your config file...';
		
		Installer::add_to_config('MAP_CENTER_LAT', '45.484400');
		Installer::add_to_config('MAP_CENTER_LNG', '-62.334821');
		Installer::add_to_config('ACARS_DEBUG', false);
		Installer::add_to_config('SIGNATURE_SHOW_EARNINGS', true);
		Installer::add_to_config('SIGNATURE_SHOW_RANK_IMAGE', true);
		Installer::add_to_config('BLANK', '');
		Installer::add_to_config('AVATAR_FILE_SIZE', 50000);
		Installer::add_to_config('AVATAR_MAX_WIDTH', 80);
		Installer::add_to_config('AVATAR_MAX_HEIGHT', 80);
	}
	elseif($version < 11441)
	{
		Installer::sql_file_update(SITE_ROOT . '/install/update_441.sql');
	}
	elseif($version < 11458)
	{
		
		Installer::add_to_config('PAGE_ENCODING', 'ISO-8859-1', 'This is the page encoding');
		Installer::add_to_config('PILOTID_LENGTH', 4, 'This is the length of the pilot ID. including leading zeros');
		Installer::add_to_config('SIGNATURE_TEXT_COLOR', '#FFF');
		Installer::add_to_config('SIGNATURE_SHOW_COPYRIGHT', true);
		
		# Update signatures for everyone
		$allpilots = PilotData::GetAllPilots();		
		echo "Generating signatures<br />";		
		foreach($allpilots as $pilot)
		{
			echo "Generating signature for $pilot->firstname $pilot->lastname<br />";
			PilotData::GenerateSignature($pilot->pilotid);
		}
	}
	else
	{
		echo '<p>Adding new options to the core/local.config.php...</p>';
		Installer::add_to_config('LOAD_FACTOR', '72'); 
		Installer::add_to_config('CARGO_UNITS', 'lbs');
		
		Installer::add_to_config('COMMENT', 'FSPassengers Settings');
		Installer::add_to_config('COMMENT', 'Units settings');
		Installer::add_to_config('WeightUnit', '1', '0=Kg 1=lbs');
		Installer::add_to_config('DistanceUnit', '2', '0=KM 1= Miles 2=NMiles');
		Installer::add_to_config('SpeedUnit', '1', '0=Km/H 1=Kts');
		Installer::add_to_config('AltUnit', '1', '0=Meter 1=Feet');
		Installer::add_to_config('LiquidUnit', '2', '0=liter 1=gal 2=kg 3=lbs');
		Installer::add_to_config('WelcomeMessage', SITE_NAME.' ACARS', 'Welcome Message');
		
		Installer::add_to_config('COMMENT', 'Monetary Units');
		Installer::add_to_config('MONEY_UNIT', '$', '$, �, etc');
		
		Installer::add_to_config('COMMENT', 'Start Date - Enter the month/year your VA started');
		# Installer::add_to_config('VA_START_DATE', 'January 2008');
		
		echo '<p>Updating your database...</p>';
		Installer::sql_file_update(SITE_ROOT . '/install/update.sql');
		
	}
		
	
# Final version update
if(!isset($_GET['test']))
{
	$sql = 'UPDATE `phpvms_settings` 
				SET value=\''.UPDATE_VERSION.'\' 
				WHERE name=\'PHPVMS_VERSION\'';
				
	DB::query($sql);
}

echo '<p><strong>Update completed!</strong></p>
		<hr>
	  <p style="width:500px">If there were any errors, you may have to manually run the SQL update, or correct the errors, and click the following to re-run the update: <a href="update.php?force">update.php?force</a></p>
	  <p>Click here to <a href="'.SITE_URL.'">goto your site</a>, or <a href="'.SITE_URL.'/admin">your admin panel</a></p>  ';

# Don't count forced updates
if(!isset($_GET['force']))
{
	Installer::RegisterInstall(UPDATE_VERSION);
}

Template::Show('footer.tpl');