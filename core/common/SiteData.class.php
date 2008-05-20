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
 * @package phpvms
 * @subpackage news_and_pages
 */
 
class SiteData
{

	function GetAllNews()
	{
		return DB::get_results('SELECT id, subject, body, UNIX_TIMESTAMP(postdate) as postdate, postedby
									FROM ' . TABLE_PREFIX.'news ORDER BY postdate DESC');
	}
	
	function AddNewsItem($subject, $body)
	{
		$postedby = Auth::$userinfo->firstname . ' ' . Auth::$userinfo->lastname;
		
		$sql = 'INSERT INTO ' . TABLE_PREFIX . "news (subject, body, postdate, postedby)
					VALUES ('$subject', '$body', NOW(), '$postedby')";
					
		return DB::query($sql);		
	}
	
	function DeleteItem($id)
	{
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'news WHERE id='.$id;
		
		return DB::query($sql);
	}
	
	function GetAllPages($onlyenabled=false, $onlypublic=true)
	{
		$sql = "SELECT * FROM ".TABLE_PREFIX."pages";
		
		if($onlyenabled == true)
		{
			$sql .= ' WHERE enabled=1';
			
			if($onlypublic == true)
			{
				$sql.= ' AND public=1';
			}
			
		}
		
		$ret = DB::get_results($sql);
		//DB::debug();
		return $ret;
	}
	
	function GetPageData($pageid)
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'pages WHERE pageid='.$pageid;
		return DB::get_row($sql);
	}
	
	function AddPage($title, $content, $public=true, $enabled=true)
	{
		$filename = strtolower($title);
	
		//TODO: replace this with a regex
		$filename = str_replace(' ', '', $filename);
		$filename = str_replace('?', '', $filename);
		$filename = str_replace('!', '', $filename);	
		$filename = str_replace('@', '', $filename);
		$filename = str_replace('.', '', $filename);
		$filename = str_replace(',', '', $filename);
		$filename = str_replace('\'', '', $filename);
				
		$filename = str_replace('+', 'and', $filename);
		$filename = str_replace('&', 'and', $filename);
	
		//take out any slashes
		$filename = preg_replace('/(\/|\\\)++/', '', $filename);
		
		if($public == true) $public = 1;
		else $public = 0;
		
		if($enabled == true) $enabled = 1;
		else $enabled = 0;
		
		//$filename .= '.html';
		$postedby = Auth::Username();
		
		$sql = "INSERT INTO ".TABLE_PREFIX."pages (pagename, filename, postedby, postdate, public, enabled)
					VALUES ('$title', '$filename', '$postedby', NOW(), $public, $enabled)";
					
		$ret = DB::query($sql);
		
		DB::debug();
		if(!$ret)
			return false;
			
		return self::EditPageFile($filename, $content);
	}
	
	function GetPageContent($filename)
	{
		// Round-about way, I know. But it's in the name of security. If they're giving a
		//	bogus name, then it won't find it. 
		
		$sql = 'SELECT pagename, filename 
					FROM '.TABLE_PREFIX.'pages 
					WHERE filename=\''.$filename.'\'';
		$row = DB::get_row($sql);
	
		if(!$row) return ;
		
		//run output buffering, so we can parse any PHP in there
		if(!file_exists(PAGES_PATH . '/' . $row->filename . PAGE_EXT))
		{
			return;
		}
		
		ob_start();
		include PAGES_PATH . '/' . $row->filename . PAGE_EXT; 
		$row->content = ob_get_contents();
		ob_end_clean();
		
	
		return $row;
	}
	
	function EditFile($pageid, $content, $public, $enabled)
	{
		$pagedata = SiteData::GetPageData($pageid);
		
		if($public == true) $public = 1;
		else $public = 0;
		
		if($enabled == true) $enabled = 1;
		else $enabled = 0;
		
		$sql = 'UPDATE '.TABLE_PREFIX.'pages 
				  SET public='.$public.', enabled='.$enabled.'
				  WHERE pageid='.$pageid;
		
		DB::query($sql);
		
		if(self::EditPageFile($pagedata->filename, stripslashes($content)))
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	function EditPageFile($filename, $content)
	{
		//create the file
		$filename = PAGES_PATH . '/' . $filename . PAGE_EXT;
		$fp = fopen($filename, 'w');
		
		if(!$fp) 
		{
			return false;
		}
		else 
		{
			fwrite($fp, $content, strlen($content));
			fclose($fp);
			return true;
		}
	}
}