<?
class SIDNTransaction
{
	const TEMPLATE_DIR                = "/var/www/crm/templates/api/sidn/";
	//const HOSTMASTER           	  = "drs@domain-registry.nl";
	const HOSTMASTER          	  = "sam@redunix.nl";
	const FORM_NEW                    = "aanvragen nieuwe domeinnaam.txt";
	const FORM_DELETE                 = "opheffen domeinnaam.txt";
	const FORM_MOVE                   = "verhuizen domeinnaam.txt";
	const FORM_CHANGE_OWNER           = "wijzigen domeinnaamhouder.txt";
	const FORM_CHANGE_OWNER_DETAILS   = "wijzigen houdergegevens.txt";
	const FORM_CHANGE_CONTACT         = "wijzigen contactpersonen.txt";
	const FORM_CHANGE_NAMESERVERS     = "wijzigen nameservers.txt";
	const FORM_CHANGE_CONTACT_DETAILS = "wijzigen contactpersoongegevens.txt";

	//get template and merge data
	public static function createForm($form,$values,$ns = NULL, $owner = NULL, $adminc = NULL, $techc = NULL)
	{
		if (is_object($ns))     $ns     = get_object_vars($ns); 
		if (is_object($owner))  $owner  = get_object_vars($owner); 
		if (is_object($adminc)) $adminc = get_object_vars($adminc); 
		if (is_object($techc))  $techc  = get_object_vars($techc); 

		//extra fields (required by SIDN)
		$owner["rechtsvorm"] = "ANDERS";
		$owner["country"]    = "NL";
		$owner["gender"]     = "M";
		$owner_address       = explode("\n",$owner["address"]);
		$owner["street"]     = preg_replace("/[0-9]+/","",$owner_address[0]);
		$owner["housenr"]    = preg_replace("/.*([0-9]+).*/","\\1",$owner_address[0]);
		$owner["zipcode"]    = trim(substr($owner_address[1],0,7));
		$owner["city"]       = trim(substr($owner_address[1],7));
		$adminc_address      = explode("\n",$adminc["address"]);
		$adminc["street"]    = $adminc_address[0];
		$adminc["zipcode"]   = trim(substr($adminc_address[1],0,7));
		$adminc["city"]      = trim(substr($adminc_address[1],7));
		$techc_address       = explode("\n",$techc["address"]);
		$techc["street"]     = $techc_address[0];
		$techc["zipcode"]    = trim(substr($techc_address[1],0,7));
		$techc["city"]       = trim(substr($techc_address[1],7));
		$owner_name          = explode(" ",$owner["name"],2);
		$owner["firstname"]  = trim($owner_name[0]);
		$owner["initials"]   = substr($owner["firstname"],0,1); 
		$owner["lastname"]   = trim($owner_name[1]); 
		$adminc_name         = explode(" ",$adminc["name"],2);
		$adminc["firstname"] = trim($adminc_name[0]);
		$adminc["initials"]  = substr($adminc["firstname"],0,1); 
		$adminc["lastname"]  = trim($adminc_name[1]); 
		$techc_name          = explode(" ",$techc["name"],2);
		$techc["firstname"]  = trim($techc_name[0]);
		$techc["initials"]   = substr($techc["firstname"],0,1); 
		$techc["lastname"]   = trim($techc_name[1]); 
		$owner["phone"]      = preg_replace("/^(\+|00)[1-9][1-9]\.?/","",$owner["phone"] );
		$adminc["phone"]     = preg_replace("/^(\+|00)[1-9][1-9]\.?/","",$adminc["phone"]);
		$techc["phone"]      = preg_replace("/^(\+|00)[1-9][1-9]\.?/","",$techc["phone"] );

		$data = file_get_contents(self::TEMPLATE_DIR . $form);

		if (!empty($owner["company"]))
		{
			$owner["firstname"] = "";
			$owner["lastname" ] = "";
			$owner["initials" ] = "";
			$owner["gender"   ] = "";
		}
		if (!empty($owner["sidn_owner"]))
		{
			$owner["firstname"] = "";
			$owner["lastname" ] = "";
			$owner["initials" ] = "";
			$owner["gender"   ] = "";
			$owner["street"   ] = "";
			$owner["housenr"  ] = "";
			$owner["zipcode"  ] = "";
			$owner["city"     ] = "";
			$owner["phone"    ] = "";
			$owner["company"  ] = "";
			$owner["rechtsvorm"] = "";
			$owner["country"  ] = "";
			$owner["gender"   ] = "";
		}

		if (!empty($ns))     foreach ($ns      as $key => $value)
			$data = str_replace("\$nameservers_" . $key,$value,$data);
		if (!empty($owner))  foreach ($owner   as $key => $value)
			$data = str_replace("\$owner_" . $key,$value,$data);
		if (!empty($adminc)) foreach ($adminc  as $key => $value)
			$data = str_replace("\$adminc_" . $key,$value,$data);
		if (!empty($techc))  foreach ($techc   as $key => $value)
			$data = str_replace("\$techc_"  . $key,$value,$data);
		foreach ($values as $key => $value)
			$data = str_replace("\$"       . $key,$value,$data);
	print $data;
		return $data;
	}

	//create email and send to sidn
	public static function mail($form)
	{
		return mail(self::HOSTMASTER,"REDUNIX Automated message for SIDN",$form,"From: hostmaster@panther-it.nl","-f hostmaster@panther-it.nl");
	}

	public static function send($form,$values,$ns = NULL,$owner = NULL,$adminc = NULL,$techc = NULL)
	{
		$form = self::createForm($form,$values,$ns,$owner,$adminc,$techc);
		self::mail($form);
		return $form;
	}
}

?>
