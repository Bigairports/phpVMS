<?php

class Settings extends ModuleBase
{
		
	function NavBar()
	{
		echo '<li><a href="#">Settings</a>
					<ul>
						<li><a href="?admin=settings">General Settings</a></li>
						<li><a href="?admin=customfields">Custom Profile Fields</a></li>
					</ul>
				</li>';
	}
	
	function Controller()
	{		
		//$this->TEMPLATE->template_path = dirname(__FILE__) . '/templates';
		
		if(Vars::GET('admin') == 'settings')
		{
		
			// Check for POST here since we'll be outputting the form again
			// jQuery will replace the entire <div> with fresh updated content
		
			switch(Vars::POST('action'))
			{
				case 'addsetting':
					$this->AddSetting();
					break;
				case 'savesettings':
					$this->SaveSettings();
					break;
			}
						
			$this->ShowSettings();
		}
		
		/* CustomFields Section
		 */
		elseif(Vars::GET('admin') == 'customfields')
		{
			switch(Vars::POST('action'))
			{
				case 'savefields':
					$this->SaveFields();
					break;
					
				case 'addfield':
					$this->AddField();
					break;
					
				case 'deletefield':
					$this->DeleteField();
					break;
			}
			
			$this->ShowFields();
		}
		
	}
		
	function SaveSettings()
	{
		while(list($name, $value) = each($_POST))
		{
			if($name == 'action') continue;
			elseif($name == 'submit') continue;
			
			$value = DB::escape($value);
			SettingsData::SaveSetting($name, $value, '', false);
		}		
		
		echo '<div id="messagebox">Settings were saved!</div>';
	}
	
	function AddField()
	{
		if(Vars::POST('fieldname') == '')
		{
			echo 'No field name entered!';
			return;
		}
		
		echo '<div id="messagebox">';
		if(SettingsData::AddField())
			echo 'Settings saved';
		else
			echo 'There was an error saving the settings: ' . DB::$err;
		
		echo '</div>';
	}
	
	function SaveFields()
	{
		
		print_r($_POST);
		
	}
	
	function DeleteField()
	{
		$id = DB::escape(Vars::POST('id'));
		
		echo '<div id="messagebox">';
		if(SettingsData::DeleteField($id) == true)
		{
			echo 'The custom field was deleted!';
		}
		else
		{
			echo 'There was an error deleting the field: '. DB::$err;
		}
		
		echo '</div>';	
	}
	
	function ShowSettings()
	{
		Template::Set('allsettings', SettingsData::GetAllSettings());
		
		Template::ShowTemplate('settings_mainform.tpl');
		//$this->TEMPLATE->ShowTemplate('addsetting.tpl');
	}
	
	function ShowFields()
	{
		Template::Set('allfields', SettingsData::GetAllFields());
		
		Template::ShowTemplate('settings_customfieldsform.tpl');
		Template::ShowTemplate('settings_addcustomfield.tpl');
		
	}
}
?>