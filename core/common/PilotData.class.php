<?php


class PilotData
{
	
	function GetAllPilots($letter='')
	{
		$sql = 'SELECT * FROM ' . TABLE_PREFIX .'users ';
		
		if($letter!='')
			$sql .= " WHERE lastname LIKE '$letter%' ";
		
		$sql .= ' ORDER BY lastname DESC';
		
		return DB::get_results($sql);
	}
	
	function GetPilotData($userid)
	{
		$sql = 'SELECT firstname, lastname, email, location, UNIX_TIMESTAMP(lastlogin) as lastlogin, 
						totalflights, totalhours, confirmed, retired
					FROM '.TABLE_PREFIX.'users WHERE userid='.$userid;
		
		return DB::get_row($sql);
	}
	
	function GetFieldData($userid, $inclprivate=false)
	{
		$sql = 'SELECT f.fieldname, v.value 
				FROM '.TABLE_PREFIX.'customfields f, '.TABLE_PREFIX.'fieldvalues v
				WHERE f.fieldid=v.fieldid AND v.userid='.$userid;
		
		if($inclprivate == false)
			$sql .= " AND f.public='y'";
		else	
			$sql .= "AND (f.public='n' OR f.public='y')";
			
		return DB::get_results($sql);		
	}
}

?>