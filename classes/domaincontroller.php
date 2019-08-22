<?
require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/sql/sqlcustomers.php"); 
require_once(__DIR__ . "/api/sidn.php");
require_once(__DIR__ . "/api/enom.php");
require_once(__DIR__ . "/api/registratiecentrale.php");

class DomainController 
{

        function __construct() 
        {
        }


        public static function create($values)
        {
		$owner  = SqlContacts::find($values["owner"]);
		$adminc = SqlContacts::find($values["adminc"]);
		$techc  = SqlContacts::find($values["techc"]);
		$ns     = SqlNameservers::find($values["nameservers"]);
		$domain = $values["domainname"];
		$ext    = explode(".",$domain);
		$ext    = strtolower($ext[count($ext)-1]);
		$values["extension"] = $ext;

		switch($ext)
		{
			case "nl": return SIDNTransaction::send(SIDNTransaction::FORM_NEW,$values,$ns,$owner,$adminc,$techc);
			case "be":
			case "de":
			case "eu": return RegistratieCentrale::send("domain_register"    ,$values,$ns,$owner,$adminc,$techc);
			default  : return Enom::send("Purchase"		 ,$values,$ns,$owner,$adminc,$techc);
		}
	}

	public static function update($values)
	{
		$oldValues = SqlDomains::find($values["id"]);
		$owner     = SqlContacts::find($values["owner"]);
		$adminc    = SqlContacts::find($values["adminc"]);
		$techc     = SqlContacts::find($values["techc"]);
		$ns        = SqlNameservers::find($values["nameservers"]);
		$domain = $values["domainname"];
		$ext    = explode(".",$domain);
		$ext    = strtolower($ext[count($ext)-1]);


		if ($oldValues->owner       != $values["owner"])
			switch($ext)
			{
				case "nl": return SIDNTransaction::send(SIDNTransaction::FORM_CHANGE_OWNER,$values,$ns,$owner,$adminc,$techc);
				case "be":
				case "de":
				case "eu": echo "Function not availible at registrar\n"; return true;
				default  : return Enom::send("Contacts"		          ,$values,$ns,$owner,$adminc,$techc);
			}
		if ($oldValues->adminc      != $values["adminc"])
			switch($ext)
			{
				case "nl": return SIDNTransaction::send(SIDNTransaction::FORM_CHANGE_CONTACT,$values,$ns,$owner,$adminc,$techc);
				case "be":
				case "de":
				case "eu": echo "Function not availible at registrar\n"; return true;
				default  : return Enom::send("Contacts"		          ,$values,$ns,$owner,$adminc,$techc);
			}
		if ($oldValues->techc       != $values["techc"])
			switch($ext)
			{
				case "nl": return SIDNTransaction::send(SIDNTransaction::FORM_CHANGE_CONTACT,$values,$ns,$owner,$adminc,$techc);
				case "be":
				case "de":
				case "eu": echo "Function not availible at registrar\n"; return true;
				default  : return Enom::send("Contacts"		          ,$values,$ns,$owner,$adminc,$techc);
			}
		if ($oldValues->nameservers != $values["nameservers"])
			switch($ext)
			{
				case "nl": return SIDNTransaction::send(SIDNTransaction::FORM_CHANGE_NAMESERVERS,$values,$ns,$owner,$adminc,$techc);
				case "be":
				case "de":
				case "eu": return RegistratieCentrale::send("domain_modify_ns"       ,$values,$ns,$owner,$adminc,$techc);
				default  : return Enom::send("ModifyNS"                              ,$values,$ns,$owner,$adminc,$techc); 
			}
			
		print "Nothing to do";
		return true;
	}

	public static function cancel($values)
	{
		error_log("SIDN::CancelDomain");
		$domain = $values["domainname"];
		$ext    = explode(".",$domain);
		$ext    = strtolower($ext[count($ext)-1]);
		switch($ext)
		{
			case "nl": return SIDNTransaction::send(SIDNTransaction::FORM_DELETE  ,$values); 
			case "be":
			case "de":
			case "eu": echo "Function not availible at registrar\n"; return true;
			default  : return Enom::send("SetDomainExp"		      ,$values);
		}
	}
}
?>
