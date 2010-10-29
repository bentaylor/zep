<?php
if (!defined('ABSPATH'))
	die('The ABSPATH needs to be defined to continue...');

class Persistent
{
	var $filename;
	
	function Persistent($filename)
	{
		$this->filename = $filename;
		if (!file_exists($this->filename)) $this->save();
	}
	
	function save()
	{
		if ($f = @fopen($this->filename, 'w'))
		{
			if (@fwrite($f, '<?php // ' . serialize(get_object_vars($this)) . ' ?>'))
			{
				@fclose($f);
			}
			else die('Could not write to file ' . $this->filename . ' at Persistant::save');
		}
		else die('Could not open file ' . $this->filename . ' for writing, at Persistant::save');
		
	}
	
	function open()
	{
		$vars = unserialize( substr( file_get_contents($this->filename), 9, -3 ) );
		foreach ($vars as $key => $val)
		{
			if ($key != 'filename') // we need to exclude the re-assignment of the filename
				eval("$" . "this->$key = $" . "vars['" . "$key'];");
		}
	}
}

class Settings extends Persistent
{
	var $charset = 'UTF-8';
	var $max_items = 5;
	var $date_format = 'M j Y';
	var $google_analytics;
	var $tagspace_url = 'http://technorati.com/tag/%s';
	
	function Settings( $filename = '' )
	{
		if ($filename == '')
			$filename = ABSPATH . '/admin/data/settings.php';
		
		$this->Persistent($filename);
		$this->open();
	}
}

class Profile extends Persistent
{
	var $first_name;
	var $middle_name;
	var $last_name;
	var $display_name;
	var $blurb;
	var $location;
	var $country;
	var $email;
	var $dob = array();
	var $gender;
	var $gravatar;
	var $openid_server;
	var $openid_delegate;
	
	function Profile( $filename = '' )
	{
		if ($filename == '')
			$filename = ABSPATH . '/admin/data/profile.php';
		
		$this->Persistent($filename);
		$this->open();
	}
}

class WebDataSources extends Persistent
{
	var $profiles = array();
	var $sources = array();
	var $blogs = array();
	var $bookmarks = array();
	var $photos = array();
	var $music = array();
	var $location = array();
	var $tweet = array();
	
	function WebDataSources( $filename = '' )
	{
		if ($filename == '')
			$filename = ABSPATH . '/admin/data/social-networks.php';
		
		$this->Persistent($filename);
		$this->open();
	}
}
?>