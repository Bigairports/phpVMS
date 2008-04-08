<?php



class PIREPAdmin
{
	
	function Controller()
	{
		// Post actions
		switch(Vars::POST('action'))
		{
			case 'addcomment':
				$this->AddComment();				
				break;
				
			case 'approvepirep':
				$this->ApprovePIREP();
				break;
				
			case 'rejectpirep':
				$this->RejectPIREP();
				break;
		}
		
		// Views
		switch(Vars::GET('admin'))
		{
			case 'rejectpirep':
				Template::Set('pirepid', Vars::GET('pirepid'));
				Template::Show('pirep_reject.tpl');
				
				break;
				
			case 'viewrecent':
				Template::Set('pireps', PIREPData::GetRecentReports());
				Template::Set('descrip', 'These pilot reports are from the past 48 hours');
				
				Template::Show('pireps_list.tpl');
				break;
				
			case 'viewpending':
				Template::Set('pireps', PIREPData::GetAllReportsByAccept(PIREP_PENDING));				
				Template::Show('pireps_list.tpl');
				
				break;
				
			case 'viewall':
				
				if($_GET['start'] == '')
					$_GET['start'] = 0;
				
				$num_per_page = 20;
				$allreports = PIREPData::GetAllReports($_GET['start'], $num_per_page);
				
				if(count($allreports) >= $num_per_page)
				{
					Template::Set('paginate', true);
					Template::Set('admin', 'viewall');
					Template::Set('start', intval($_GET['start'])+20);
				}
				
				Template::Set('pireps', $allreports);
				Template::Show('pireps_list.tpl');
				
				break;
				
			case 'addcomment':
				Template::Set('pirepid', Vars::GET('pirepid'));
				
				Template::Show('pirep_addcomment.tpl');
				break;
		}
	}
	
	function AddComment()
	{
		$comment = Vars::POST('comment');
		$commenter = Auth::$userinfo->pilotid;
		$pirepid = Vars::POST('pirepid');
	
		$pirep_details = PIREPData::GetReportDetails($pirepid);
		
		PIREPData::AddComment($pirepid, $commenter, $comment);
		
		// Send them an email
		Template::Set('firstname', $pirep_details->firstname);
		Template::Set('lastname', $pirep_details->lastname);
		Template::Set('pirepid', $pirepid);
		
		$message = Template::GetTemplate('email_commentadded.tpl', true);
		Util::SendEmail($pirep_details->email, 'Comment Added', $message);			
	}
	
	/**
	 * Approve the PIREP, and then update
	 * the pilot's data
	 */
	function ApprovePIREP()
	{
		$pirepid = Vars::POST('id');
		
		if($pirepid == '') return;
			
		$pirep_details  = PIREPData::GetReportDetails($pirepid);
		
		if(intval($pirep_details->accepted) == PIREP_ACCEPTED) return;
	
		PIREPData::ChangePIREPStatus($pirepid, PIREP_ACCEPTED); // 1 is accepted
		PilotData::UpdateFlightData(Auth::$userinfo->pilotid, $pirep_details->flighttime);	
	}
	
	/**
	 * Reject the report, and then send them the comment
	 * that was entered into the report
	 */
	function RejectPIREP()
	{
		$pirepid = Vars::POST('pirepid');
		$comment = Vars::POST('comment');
				
		if($pirepid == '' || $comment == '') return;
	
		PIREPData::ChangePIREPStatus($pirepid, PIREP_REJECTED); // 2 is rejected
		$pirep_details = PIREPData::GetReportDetails($pirepid);
		
		// If it was previously accepted, subtract the flight data
		if(intval($pirep_details->accepted) == PIREP_ACCEPTED)
		{
			PilotData::UpdateFlightData(Auth::$userinfo->pilotid, -1 * floatval($pirep->flighttime), -1);
		}
		
		// Send comment for rejection	
		if($comment != '')
		{
			$commenter = Auth::$userinfo->pilotid;
			PIREPData::AddComment($pirepid, $commenter, $comment);
			
			// Send them an email
			Template::Set('firstname', $pirep_details->firstname);
			Template::Set('lastname', $pirep_details->lastname);
			Template::Set('pirepid', $pirepid);
			
			$message = Template::GetTemplate('email_commentadded.tpl', true);
			Util::SendEmail($pirep_details->email, 'Comment Added', $message);	
		}
	}
}

?>