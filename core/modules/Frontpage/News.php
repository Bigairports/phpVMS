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
 * @package module_news
 */

class News
{
	// This function gets called directly in the template
	function ShowNewsFront()
	{
		
		$sql = 'SELECT id, subject, body, postedby, UNIX_TIMESTAMP(postdate) AS postdate
				 FROM ' . TABLE_PREFIX .'news ORDER BY postdate DESC LIMIT 5';
		
		$res = DB::get_results($sql);
		
		if(!$res)
			return;
			
		foreach($res as $row)
		{
			//TODO: change the date format to a setting in panel
			Template::Set('subject', $row->subject);
			Template::Set('body', $row->body);
			Template::Set('postedby', $row->postedby);
			Template::Set('postdate', date('m/d/Y', $row->postdate));
		
			Template::Show('news_newsitem.tpl');
		}
	}
}