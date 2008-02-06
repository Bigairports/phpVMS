<?php


include dirname(__FILE__) . '/SettingsData.class.php';

class Settings extends ModuleBase
{
		
	function NavBar()
	{
		echo '<li><a href="?admin=settings">Settings</a></li>';
	}
	
	
	function Controller()
	{		
	
		$this->TEMPLATE->template_path = dirname(__FILE__) . '/templates';
		
		if(Vars::GET('admin') == 'settings')
		{
		
			if(Vars::POST('submit') != '')
			{
				$this->AddSetting();
			}
			
			$this->ShowSettings();
		}
		
	}
	
	function AddSetting()
	{
		print_r($_POST);
		
	}
	
	function ShowSettings()
	{
		$allsettings = DB::get_results('SELECT * FROM ' . TABLE_PREFIX.'settings');
			
			$this->TEMPLATE->Set('allsettings', $allsettings);
			$this->TEMPLATE->ShowTemplate('settingsform.tpl');
			
			
			$this->TEMPLATE->ShowTemplate('addsetting.tpl');
	}
}
?>