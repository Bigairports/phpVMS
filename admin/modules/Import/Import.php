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

class Import extends CodonModule
{
	function HTMLHead()
	{		
		switch($this->get->page)
		{
			case '':
			default:
			case 'processimport':
				
				Template::Set('sidebar', 'sidebar_import.tpl');
				
				break;
		}
	}
	
	function Controller()
	{
		
		switch($this->get->page)
		{
			default:
			case '':
				
				Template::Show('import_form.tpl');
				
				break;
				
			case 'processimport':
				
				echo '<h3>Processing Import</h3>';
				
				if(!file_exists($_FILES['uploadedfile']['tmp_name']))
				{
					Template::Set('message', 'File upload failed!');
					Template::Show('core_error.tpl');
					return;
				}
				
				echo '<p><strong>DO NOT REFRESH OR STOP THIS PAGE</strong></p>';
				
				set_time_limit(270);
				$errs = array();
				$skip = false;
				
				$fp = fopen($_FILES['uploadedfile']['tmp_name'], 'r');
				
				if(isset($_POST['header'])) $skip = true;
				
				while($fields = fgetcsv($fp, 1000, ','))
				{
					// Skip the first line
					if($skip == true)
					{
						$skip = false;
						continue;
					}
					
					// list fields:
					$code = $fields[0];
					$flightnum = $fields[1];
					$leg = $fields[2];
					$depicao = $fields[3];
					$arricao = $fields[4];
					$route = $fields[5];
					$aircraft = $fields[6];
					$distance = $fields[7];
					$deptime = $fields[8];
					$arrtime = $fields[9];
					$flighttime = $fields[10];
					$notes = $fields[11];
					$maxload = $fields[12];
					$price = $fields[13];
					$type = $fields[14];
									
					if($code=='')
					{
						continue;
					}
					
					// Check the code:
					if(!OperationsData::GetAirlineByCode($code))
					{
						echo "Airline with code $code does not exist! Skipping...<br />";
						continue;
					}
					
					// Make sure airports exist:
					$url = 'http://ws.geonames.org/search?maxRows=1&featureCode=AIRP&q=';
					if(!($depapt = OperationsData::GetAirportInfo($depicao)))
					{
						# Add the airport if it doesn't exist
						echo "ICAO $depicao not added... retriving information: <br />";
						
						$reader = simplexml_load_file($url.$depicao);
						if($reader->totalResultsCount == 0 || !$reader)
						{
							echo "Could not retrieve information about $depicao, try again, skipping for now... <br /><br />";
							continue;
						}
						else
						{
							echo "Found: $depicao - ".$reader->geoname->name
									.' ('.$reader->geoname->lat.','.$reader->geoname->lng.'), airport added<br /><br />';

							// Add the AP
							OperationsData::AddAirport($depicao, $reader->geoname->name, $reader->geoname->countryName,
										$reader->geoname->lat, $reader->geoname->lng, false);
						}
					}
					
					if(!($arrapt = OperationsData::GetAirportInfo($arricao)))
					{
						echo "ICAO $arricao not added... retriving information: <br />";
						
						$reader = simplexml_load_file($url.$arricao);
						if($reader->totalResultsCount == 0 || !$reader)
						{
							echo "Could not retrieve information about $arricao, try again, skipping for now...<br /><br />";
							continue;
						}
						else
						{
							echo "Found: $depicao - ".$reader->geoname->name
									.' ('.$reader->geoname->lat.','.$reader->geoname->lng.'), airport added<br /><br />';
									
							// Add the AP
							OperationsData::AddAirport($arricao, $reader->geoname->name, 
										$reader->geoname->countryName,
										$reader->geoname->lat, $reader->geoname->lng, false);
						}
						
					}
					
					# Check the aircraft
					$aircraft = trim($aircraft);
					$ac_info = OperationsData::GetAircraftByReg($aircraft);
					
					# If the aircraft doesn't exist, skip it
					if(!$ac_info)
					{
						echo 'Aircraft "'.$aircraft.'" does not exist! Skipping<br />';
						continue;
					}
					
					$ac = $ac_info->id;
					
					# This is our 'struct' we're passing into the schedule function
					#	to add or edit it
					
					$data = array(	'scheduleid'=>$schedinfo->id,
									'code'=>$code,
									'flightnum'=>$flightnum,
									'leg'=>$leg,
									'depicao'=>$depicao,
									'arricao'=>$arricao,
									'route'=>$route,
									'aircraft'=>$ac,
									'distance'=>$distance,
									'deptime'=>$deptime,
									'arrtime'=>$arrtime,
									'flighttime'=>$flighttime,
									'notes'=>$notes,
									'enabled'=>true,
									'maxload'=>$maxload,
									'price'=>$price,
									'flighttype'=>$type);
						
					# Check if the schedule exists:
					if(($schedinfo = self::GetScheduleByFlight($code, $flightnum, $leg)))
					{
						# Update the schedule instead
						$val = SchedulesData::EditSchedule($data);
					
					}
					else
					{
						# Add it
					
						/*$val = SchedulesData::AddSchedule($code, $flightnum, $leg, $depicao, $arricao,
										$route, $ac, $distance, $deptime, $arrtime, $flighttime, $notes);*/
						$val = SchedulesData::AddSchedule($data);
										
					}
					
					if($val === false)
					{
						if(DB::errno() == 1216)
						{
							echo "Error adding $code$flightnum: The airline code, airports, or aircraft does not exist";
						}
						else
						{
							$error = (DB::error() != '') ? DB::error() : 'Route already exists';
							echo "$code$flightnum was not added, reason: $error";
						}
						
						echo '<br />';
					}
					else
					{
						echo "Imported $code$flightnum ($depicao to $arricao)<br />";
					}
				}
				
				echo 'The import process is complete!<br />';
				
				foreach($errs as $error)
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$error.'<br />';
				}
				
				break;
		}
	}
}
?>