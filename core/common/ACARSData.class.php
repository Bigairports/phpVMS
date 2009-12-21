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
 
class ACARSData extends CodonData
{
	public static $lasterror;
	public static $pirepid;
	
	/**
	 * This updates the ACARS live data for a pilot
	 *
	 * @param mixed $data This is the data structure with flight properties
	 * @return mixed Nothing
	 *
	 */
	public static function UpdateFlightData($data)
	{
		if(!is_array($data))
		{
			self::$lasterror = 'Data not array';
			return false;
		}
		
		// make sure at least the vitals we need are there:
		if($data['pilotid'] == '')
		{
			self::$lasterror = 'No pilot ID specified';
			return false;
		}
		
		if($data['flightnum'] == '')
		{
			self::$lasterror = 'No flight number';
			return false;
		}
		
		if($data['depicao'] == '' || $data['arricao'] == '' || $data['lat'] == '' || $data['lng'] == '')
		{
			self::$lasterror = 'Airports are blank';
			return;
		}

		if(!is_numeric($data['pilotid']))
		{
			preg_match('/^([A-Za-z]*)(\d*)/', $data['pilotid'], $matches);
			$code = $matches[1];
			$data['pilotid'] = $matches[2];
		}
		
		if(isset($data['code']))
		{
			$data['flightnum'] = $data['code'].$data['flightnum'];
		}
		
		// Add pilot info		
		$pilotinfo = PilotData::GetPilotData($data['pilotid']);
		$data['pilotname'] = $pilotinfo->firstname . ' ' . $pilotinfo->lastname;
		
		// Store for later
		if(isset($data['registration']))
		{
			$ac_registration = $data['registration'];
			unset($data['registration']);
		}
		
		// Get the airports data
		$dep_apt = OperationsData::GetAirportInfo($data['depicao']);
		$arr_apt = OperationsData::GetAirportInfo($data['arricao']);
		$dep_apt->name = DB::escape($dep_apt->name);
		$arr_apt->name = DB::escape($arr_apt->name);
		
		// Clean up times
		if(!is_numeric($data['deptime']))
			$data['deptime'] = strtotime($data['deptime']);
			
		if(!is_numeric($data['arrtime']))
			$data['arrtime'] = strtotime($data['arrtime']);
		
		/* Check the heading for the flight
			If none is specified, then point it straight to the arrival airport */
		if($data['heading'] == '' || $data['heading'] == null || !isset($data['heading']))
		{
			/* Calculate an angle based on current coords and the
				destination coordinates */
			
			$data['heading'] = intval(atan2(($data['lat'] - $arr_apt->lat), ($data['lng'] - $arr_apt->lng)) * 180/3.14);
			//$flight->heading *= intval(180/3.14159);
			
			if(($data['lat'] - $data['lng']) < 0)
			{
				$data['heading'] += 180;
			}
			
			if($data['heading'] < 0)
			{
				$data['heading'] += 360;
			}
		}
	
		// first see if we exist:
		$sql = 'SELECT `id`
				FROM '.TABLE_PREFIX."acarsdata 
				WHERE `pilotid`={$data['pilotid']}";
				
		$exist = DB::get_row($sql);
			
		$flight_id = '';
		
		if($exist)
		{ // update
			
			$upd = array();
			$flight_id = $exist->id;
			
			/* Unset the pilot id so it's not updating itself
			 */
			$pilotid = $data['pilotid'];
			unset($data['pilotid']);
				
			//foreach(self::$fields as $field)
			foreach($data as $field => $value)
			{
				$value = DB::escape(trim($value));
				
				// Append the message log
				if($field == 'messagelog')
				{
					$upd[] ="`messagelog`=CONCAT(`messagelog`, '{$value}')";
				}
				// Update times
				elseif($field == 'deptime' || $field == 'arrtime')
				{
					/* If undefined, set a default time to now (penalty for malformed data?)
						Won't be quite accurate.... */
					if($value == '') $value = time();
					
					$upd[] = "`{$field}`=FROM_UNIXTIME({$value})";
				}
				else 
				{					
					$upd[] = "`{$field}`='{$value}'";
				}
			}
			
			// Update Airports	
			$upd[] = "`depapt`='{$dep_apt->name}'";
			$upd[] = "`arrapt`='{$arr_apt->name}'";
			$upd[] = "`lastupdate`=NOW()";
			
			$upd = implode(',', $upd);
			$query = 'UPDATE '.TABLE_PREFIX."acarsdata 
						SET {$upd} 
						WHERE `id`='{$flight_id}'";
						
			DB::query($query);
		}
		else
		{
			// form array with $ins[column]=value and then
			//	give it to quick_insert to finish
			$ins = array();
			$vals = array();
			
			foreach($data as $field => $value)
			{
				$ins[] = "`{$field}`";
				if($field == 'deptime' || $field == 'arrtime')
				{
					if($value == '') $value = time();
					$vals[] = "FROM_UNIXTIME({$value})";
				}
				else
				{
					$value = DB::escape($value);
					$vals[] = "'{$value}'";
				}
			}
			
			// Manually add the last set
			$ins[] = '`lastupdate`'; $vals[] = 'NOW()';
			$ins[] = '`depapt`'; $vals[] = "'{$dep_apt->name}'";
			$ins[] = '`arrapt`'; $vals[] = "'{$arr_apt->name}'";
			
			$ins = implode(',', $ins);
			$vals = implode(',', $vals);
			
			$query = 'INSERT INTO '.TABLE_PREFIX."acarsdata ({$ins}) 
						VALUES ({$vals})";
		
			DB::query($query);
			
			$data['deptime'] = time();
			$flight_id = DB::$insert_id;
		}
		
		
		$flight_info = self::get_flight_by_id($flight_id);
		
		// Add this cuz we need it
		$data['code'] = $pilotinfo->code;
		$data['pilotid'] = $pilotid;
		$data['unique_id'] = $flight_id;
		$data['aircraft'] = $flight_info->aircraftname;
		$data['registration'] = $flight_info->registration;
		
		$res = CentralData::send_acars_data($data);
		return true;
	}
	
	public static function get_flight_by_id($id)
	{
		$id = intval($id);
		$sql = 'SELECT a.*, c.name as aircraftname, c.registration as registration,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname
				FROM ' . TABLE_PREFIX .'acarsdata a
				LEFT JOIN '.TABLE_PREFIX.'aircraft c ON a.`aircraft`= c.`id`
				LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = a.depicao
				LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = a.arricao
				LEFT JOIN '.TABLE_PREFIX.'pilots p ON a.`pilotid`= p.`pilotid`
				WHERE a.id='.$id;
		
		return DB::get_row($sql);
	}
	
	public static function get_flight_by_pilot($pilotid)
	{
		$pilotid = intval($pilotid);
		$sql = 'SELECT * FROM '.TABLE_PREFIX."acarsdata 
					WHERE `pilotid`='{$pilotid}'";
		
		return DB::get_row($sql);		
	}
	
	public static function get_flight($code, $flight_num)
	{
		$code = DB::escape($code);
		$flight_num = DB::escape($flight_num);
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX."acarsdata 
					WHERE flightnum='{$code}{$flight_num}'";
		
		return DB::get_row($sql);		
	}
	
	/**
	 * File a PIREP from an ACARS program
	 *
	 * @param mixed $pilotid The pilot ID of the pilot filing the PIREP
	 * @param mixed $data This is the data structure with the PIREP info
	 * @return bool true/false
	 *
	 */
	public static function FilePIREP($pilotid, $data)
	{
		if(!is_array($data)) {
			self::$lasterror = 'PIREP data must be array';
			return false;
		}
		
		# Call the pre-file event
		#
		if(CodonEvent::Dispatch('pirep_prefile', 'PIREPS', $_POST) == false)
		{
			return false;
		}
		
		# File the PIREP report
		#  
		$ret = PIREPData::FileReport($data);
		
		if(!$ret)
			return false;
			
		self::$pirepid = DB::$insert_id;
		
		# Call the event
		#
		CodonEvent::Dispatch('pirep_filed', 'PIREPS', $_POST);
		
		# Close out a bid if it exists
		#
		$bidid = SchedulesData::GetBidWithRoute($pilotid, $data['code'], $data['flightnum']);
		if($bidid)
		{
			SchedulesData::RemoveBid($bidid->bidid);
		}
		
		return true;
	}
	
	public static function GetAllFlights()
	{
		$sql = 'SELECT a.*, c.name as aircraftname, c.registration as registration,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname
					FROM ' . TABLE_PREFIX .'acarsdata a
					LEFT JOIN '.TABLE_PREFIX.'aircraft c ON a.`aircraft`= c.`registration`
					LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = a.depicao
					LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = a.arricao
					LEFT JOIN '.TABLE_PREFIX.'pilots p ON a.`pilotid`= p.`pilotid`';
		
		return DB::get_results($sql);
	}

	
	/**
	 * This returns all of the current ACARS flights within the cutoff
	 *
	 * @param int $cutofftime This is the cut-off time in hours (12 hours return in)
	 * @return array Returns an array of objects with the ACARS data
	 *
	 */
	public static function GetACARSData($cutofftime = '')
	{
		//cutoff time in days
		if($cutofftime == '' && $cutofftime != null)
		{
			// Go from minutes to hours
			$cutofftime = Config::Get('ACARS_LIVE_TIME') / 60;
			//$cutofftime = $cutofftime / 60;			
		}
		else
		{
			$cutofftime = 12;
		}
		
		/*$sql = "DELETE FROM ".TABLE_PREFIX."acarsdata a
					WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' HOUR) > a.`lastupdate`'";
		
		DB::query($sql);
		*/	
		
		$sql = 'SELECT a.*, c.name as aircraftname,
					p.code, p.pilotid as pilotid, p.firstname, p.lastname,
					dep.name as depname, dep.lat AS deplat, dep.lng AS deplng,
					arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlng
				FROM ' . TABLE_PREFIX .'acarsdata a
				LEFT JOIN '.TABLE_PREFIX.'aircraft c ON a.`aircraft`= c.`registration`
				LEFT JOIN '.TABLE_PREFIX.'pilots p ON a.`pilotid`= p.`pilotid`
				LEFT JOIN '.TABLE_PREFIX.'airports AS dep ON dep.icao = a.depicao
				LEFT JOIN '.TABLE_PREFIX.'airports AS arr ON arr.icao = a.arricao
				WHERE DATE_SUB(NOW(), INTERVAL '.$cutofftime.' HOUR) <= a.`lastupdate`';
		
		return DB::get_results($sql);
	}
}