<?php
namespace Plugin;

class Security
{
	public static function checkURL($address)
	{
		if(filter_var($address, FILTER_VALIDATE_URL) == FALSE)
		{
			$check = file_get_contents('https://sb-ssl.google.com/safebrowsing/api/lookup?client=api&apikey='.$config["google_phishing_api_key"].'&appver=1.0&pver=3.0&url='.$address);
			
			if($check == "ok")
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	public static function checkFile($file)
	{
		if(is_file($file))
		{
			$result = shell_exec("clamscan --no-summary -i ".$file);
			
			if($result == "")
				return true;
			else
				return false;
		}
		else
			return false;
	}
}
