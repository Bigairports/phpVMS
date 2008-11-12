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
 
class XML extends CodonModule
{
	public function Controller()
	{
		switch($this->get->request)
		{
			
			#
			# Get all of the current acars flight
			#	Output in XML
			#
			case 'acarsdata':
				
				header('Content-type: text/xml'); 
				
				echo $this->GenerateACARSMapXML();
				
				break;
			
			#
			# Get XML-ized output for the flight plan (dept/arr)
			#
			case 'flightdata':
				
				header('Content-type: text/xml');
				$flightnum = $_GET['route'];
				
				break;
			
		}
	}
	
	/**
	 * This generates the XML for the live ACARS map
	 */
	public function GenerateACARSMapXML()
	{
		$output = '';
		$flights = ACARSData::GetACARSData(Config::Get('ACARS_LIVE_TIME'));
		
		$output = '<livemap>';
		
		foreach($flights as $flight)
		{
			#
			# Grab some basic info about the pilot
			#			
			preg_match('/^([A-Za-z]{2,3})(\d*)/', $flight->pilotid, $matches);
			$pilotid = $matches[2];
			$pilotinfo = PilotData::GetPilotData($pilotid);
			
			#
			# Start our output
			#
			$output.='<aircraft flightnum="'.$flight->flightnum.'" lat="'.$flight->lat.'" lng="'.$flight->lng.'">';
			
		
			#
			# Pilot and Route Information
			#
			$output.='<pilotid>'.$flight->pilotid.'</pilotid>';
			$output.='<pilotname>'. $pilotinfo->firstname.' '.$pilotinfo->lastname.'</pilotname>';
			$output.='<depicao>'.$flight->depicao.'</depicao>';
			$output.='<arricao>'.$flight->arricao.'</arricao>';
			$output.='<phase>'.$flight->phasedetail.'</phase>';
			$output.='<alt>'.$flight->alt.'</alt>';
			$output.='<gs>'.$flight->gs.'</gs>';
			$output.='<distremain>'.$flight->distremain.'</distremain>';
			$output.='<timeremain>'.$flight->timeremaining.'</timeremain>';
			
			#
			# Set the icon
			#
			$output.='<icon>';
			
				if($flight->phasedetail != 'Boarding' && $flight->phasedetail != 'Taxiing'
					&& $flight->phasedetail != 'FSACARS Closed' && $flight->phasedetail != 'Taxiiing to gate'
					&& $flight->phasedetail != 'Landed' && $flight->phasedetail != 'Arrived')
				{
					$output.=SITE_URL.'/lib/images/inair.png';
				}
				else
				{
					$output.=SITE_URL.'/lib/images/onground.png';
				}
				
			$output.='</icon>';
			
			#
			# Show their specific flight data
			#
			$output.='<details><![CDATA['
				.'<span style="font-size: 10px; text-align:left; width: 100%" align="left">'
				.'<a href="'.SITE_URL.'/index.php/profile/view/'.$flight->pilotid.'">'.$flight->pilotid.' - ' . $pilotinfo->firstname .' ' . $pilotinfo->lastname.'</a><br />'
				.'<strong>Flight '.$flight->flightnum.'</strong> ('.$flight->depicao.' to '.$flight->arricao.')<br />'
				.'<strong>Status: </strong>'.$flight->phasedetail.'<br />'
				.'<strong>Dist/Time Remain: </strong>'.$flight->distremain.Config::Get('UNITS').'/'.$flight->timeremaining.' h:m<br />'
				.'</span>'
				.']]></details>';
			
			#
			# End the aircraft info
			#
			
			$output.='</aircraft>';
		}
		
		$output.='</livemap>';
		
		return $output;
		
	}
}

?>