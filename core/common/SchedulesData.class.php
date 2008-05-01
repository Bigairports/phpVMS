<?php
/**
 * SchedulesData
 *
 * Database model for any data related to schedules
 * 
 * @author Nabeel Shahzad <contact@phpvms.net>
 * @copyright Copyright (c) 2008, phpVMS Project
 * @license http://www.phpvms.net/license.php
 */

class SchedulesData
{


	function GetSchedule($id)
	{
		$sql = 'SELECT * FROM '. TABLE_PREFIX.'schedules WHERE id='.$id;

		return DB::get_row($sql);
	}
	
	function GetDepartureAirports($code='')
	{	
		$sql = 'SELECT DISTINCT s.depicao AS icao, a.name 
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'airports a
					WHERE s.depicao = a.icao ';
					
		if($code != '')
			$sql .= ' AND s.code=\''.$code.'\' ';
			
		$sql .= ' ORDER BY depicao ASC';
									
		$ret = DB::get_results($sql);		
		return $ret;
	}
	
	function GetArrivalAiports($depicao, $code='')
	{
		$sql = 'SELECT DISTINCT s.arricao AS icao, a.name 
					FROM '.TABLE_PREFIX.'schedules s, '.TABLE_PREFIX.'airports a
					WHERE s.arricao = a.icao ';

		if($code != '')
			$sql .= ' AND s.code=\''.$code.'\' ';
		
		$sql .= ' ORDER BY depicao ASC';
		
		$ret = DB::get_results($sql);		
		return $ret;
		
	}
	
	function GetRoutesWithDeparture($depicao)
	{
		$sql = 'SELECT s.*, dep.name as depname, dep.lat AS deplat, dep.lng AS deplong, 
							arr.name as arrname, arr.lat AS arrlat, arr.lng AS arrlong 
					FROM phpvms_schedules AS s
						INNER JOIN phpvms_airports AS dep ON dep.icao = s.depicao
						INNER JOIN phpvms_airports AS arr ON arr.icao = s.arricao
					WHERE s.depicao=\''.$depicao.'\'';
		
		return DB::get_results($sql);
	}
	
	function GetSchedules($limit='')
	{
		
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'schedules ORDER BY depicao DESC';
		
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
		
		return DB::get_results($sql);
	}
	
	function AddSchedule($code, $flightnum, $leg, $depicao, $arricao, $route, 
		$aircraft, $distance, $deptime, $arrtime, $flighttime)
	{
		/*
			id
			code
			flightnum
			depicao
			arricao
			route
			aircraft
			distance
			deptime
			arrtime
			flighttime
			timesflown
		*/
		
		if($leg == '') $leg = 1;
		$deptime = strtoupper($deptime);
		$arrtime = strtoupper($arrtime);
		
		if($depicao == $arricao) return;
		
		$sql = "INSERT INTO " . TABLE_PREFIX ."schedules 
				(code, flightnum, leg, depicao, arricao, route, aircraft, distance, deptime, arrtime, flighttime)
				VALUES ('$code', '$flightnum', '$leg', '$depicao', '$arricao', '$route', '$aircraft', '$distance',
				'$deptime', '$arrtime', '$flighttime')";
		
		return DB::query($sql);

	}

	function EditSchedule($scheduleid, $code, $flightnum, $leg, $depicao, $arricao, $route,
				$aircraft, $distance, $deptime, $arrtime, $flighttime)
	{
        if($leg == '') $leg = 1;
		$deptime = strtoupper($deptime);
		$arrtime = strtoupper($arrtime);


		$sql = "UPDATE " . TABLE_PREFIX ."schedules SET code='$code', flightnum='$flightnum', leg='$leg',
						depicao='$depicao', arricao='$arricao',
						route='$route', aircraft='$aircraft', distance='$distance', deptime='$deptime',
						arrtime='$arrtime', flighttime='$flighttime'
					WHERE id=$scheduleid";

		return DB::query($sql);
	}

	function DeleteSchedule($scheduleid)
	{
		$sql = 'DELETE FROM ' .TABLE_PREFIX.'schedules WHERE id='.$scheduleid;

		return DB::query($sql);
	}
}

?>