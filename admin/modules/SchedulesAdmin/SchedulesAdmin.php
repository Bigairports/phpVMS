<?php


class SchedulesAdmin
{
	function NavBar()
	{
		echo '<li><a href="#">Operations</a>
				<ul>
					<li><a href="?admin=airports">Airports</a></li>
					<li><a href="?admin=schedules">Flight Schedules</a></li>
				</ul>
				</li>';
	}
	
	function Controller()
	{
		switch(Vars::GET('admin'))
		{
			case 'airports':
			
				/* If they're adding an airport, go through this pain
				*/				 
				if(Vars::POST('action') == 'addairport')
				{
					$this->AddAirport();
				}
				 
				Template::Set('airports', SchedulesData::GetAllAirports());
				Template::Show('ops_airportlist.tpl');
				
				Template::Show('ops_addairport.tpl');
				break;
		}
	}
	
	function AddAirport()
	{
		$icao = Vars::POST('icao');	
		$name = Vars::POST('name');
		$country = Vars::POST('country');
		$lat = Vars::POST('lat');
		$long = Vars::POST('long');
		
		if($icao == '' || $name == '' || $country == '' || $lat == '' || $long == '')
		{
			Template::Set('message', 'Some fields were blank!');
			Template::Show('core_message.tpl');
			return;
		}
		
		if(($ret = SchedulesData::GetAirportInfo($icao)))
		{
			Template::Set('message', 'This airport already exists in the list');
		}
		else
		{
			if(!SchedulesData::AddAirport($icao, $name, $country, $lat, $long))
				Template::Set('message', 'There was an error adding the airport');
			else	
				Template::Set('message', 'The airport has been added');
		}
		
			
		Template::Show('core_message.tpl');
	}
}
?>