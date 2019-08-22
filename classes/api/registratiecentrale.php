<?
require_once(__DIR__ . "/registratiecentrale/transaction.php");
//require_once("../sql/sqlcontacts.php");

class RegistratieCentrale
{
	//get template and merge data
	public static function send($command,$values,$ns = NULL, $owner = NULL, $adminc = NULL, $techc = NULL)
	{
		$values["domainname"] = explode(".",$values["domainname"]);
		$billingc = SqlContacts::find("DOM004043-PANIT");

		$transaction = new RegistratieCentraleTransaction();
		$transaction->addParam( "command",$command                   );
		$transaction->addParam( "domein", strtolower($values["domainname"][0]));
 		$transaction->addParam( "tld",    strtolower($values["domainname"][1]));

		foreach (Array("registrant" => $owner
                              ,"admin"      => $adminc
                              ,"tech"       => $techc
                              ,"bill"       => $billingc ) as $contactType => $contact)
		{
		$transaction->addParam( $contactType . "_aanvrager",         empty($contact->company) ? "natuurlijkpersoon" : "rechtspersoon");
		$transaction->addParam( $contactType . "_voorletter",        $contact->initials);
		$transaction->addParam( $contactType . "_tel",		     preg_replace("/^(\+|00)[0-9][0-9][\.\- ]?/","0",$contact->phone)   );
		$transaction->addParam( $contactType . "_email",	     $contact->email   );
		$transaction->addParam( $contactType . "_tussenvoegsel",     ""                );
		$transaction->addParam( $contactType . "_achternaam",        $contact->lastname);
		$transaction->addParam( $contactType . "_bedrijfsnaam",      $contact->company );
		$transaction->addParam( $contactType . "_rechtsvorm",        "EENMANSZAAK"     );
		$transaction->addParam( $contactType . "_regnummer",         "0"               );
		$transaction->addParam( $contactType . "_straat",            $contact->street  );
		$transaction->addParam( $contactType . "_huisnr",            $contact->housenr );
		$transaction->addParam( $contactType . "_postcode",          $contact->zipcode );
		$transaction->addParam( $contactType . "_plaats",            $contact->city    );
		$transaction->addParam( $contactType . "_land",              "nl"            );
		}
		$transaction->addParam( "authkey",    			$values["authkey"] );
		$transaction->addParam( "lock",    			"true" 		   );
		$transaction->addParam( "autorenew",    		"true" 		   );
		$transaction->addParam( "idprotect",    		"false" 	   );
		$transaction->addParam( "duur",    			"1" 		   );
		$transaction->addParam( "notify",    			"true" 		   );
		$transaction->addParam( "notify_email",    		"hostmaster@redunix.nl");

		$transaction->addParam( "ns1",    $ns->ns1 );
		//$transaction->addParam( "ns1_ip", ""       );
		$transaction->addParam( "ns2",    $ns->ns2 );
		//$transaction->addParam( "ns2_ip", ""       );
		$transaction->addParam( "ns3",    $ns->ns3 );

		$transaction->DoTransaction();
		print $transaction->PostString . "\n";

		if( $transaction->Values[ "errcount" ] > 0 ) 
		{
			for( $i=1;$i<=$transaction->Values[ "errcount" ];$i++ ) 
		  		print(self::getErrorMessage($transaction->Values[ "errno".$i ]) . "\n");
			return false;
		}
		else 
			return true;
	}


	public static function getErrorMessage($i)
	{
		$errors = Array("010004" => "Domein is bezet"
                               ,"000001" => "Onbekend commando"
			       ,"000002" => "Login error"
			       ,"000003" => "Login error"
			       ,"000004" => "Login error"
			       ,"000005" => "Login error"
			       ,"000101" => "Saldo tekort"
			       ,"000201" => "Connectie error"
			       ,"000301" => "Registratie error"
			       ,"010001" => "TLD incorrect"
			       ,"010002" => "Domeinnaam incorrect"
			       ,"010003" => "Domein reeds in uw bezit"
			       ,"010004" => "Domein is bezet"
			       ,"010005" => "Domein niet in uw bezit"
			       ,"010006" => "Duits admin contact is vereist"
			       ,"010101" => "Domeinlock error"
			       ,"010102" => "Autorenew error"
			       ,"010103" => "Duur (jaren) incorrect"
			       ,"010104" => "IDProtect error"
			       ,"010105" => "gebruik_dns error"
			       ,"010201" => "Authorizatiekey incorrect"
			       ,"010202" => "Notify error"
			       ,"010203" => "Notify-emailadres incorrect"
			       ,"010301" => "Sorteer error"
			       ,"010302" => "Order error"
			       ,"010303" => "TLD error"
			       ,"010304" => "Begin error"
			       ,"020101" => "Registrant: Ongeldig (id)"
			       ,"020102" => "Registrant: Ongeldig (not allowed)"
			       ,"020103" => "Registrant: bedrijfsnaam incorrect"
			       ,"020104" => "Registrant: voorletter   incorrect"
			       ,"020105" => "Registrant: tussenvoegsel incorrect"
			       ,"020106" => "Registrant: Achternaam    incorrect"
			       ,"020107" => "Registrant: Straatnaam    incorrect"
			       ,"020108" => "Registrant: Huisnummer    incorrect"
			       ,"020109" => "Registrant: Huisnummertoev incorrect"
			       ,"020110" => "Registrant: Postcode       incorrect"
			       ,"020111" => "Registrant: Plaatsnaam     incorrect"
			       ,"020112" => "Registrant: Land           incorrect"
			       ,"020113" => "Registrant: Emailadres     incorrect"
			       ,"020114" => "Registrant: Telefoonnr     incorrect"
			       ,"020115" => "Registrant: Aanvraagtype   incorrect"
			       ,"020116" => "Registrant: Rechtsvorm     incorrect"
			       ,"020117" => "Registrant: Registratienr  incorrect"
			       ,"020118" => "Registrant: Registratienr  verplicht"
			       ,"020201" => "Admin contact: Ongeldig (id)"
			       ,"020202" => "Admin contact: Ongeldig (not allowed)"
			       ,"020203" => "Admin contact: bedrijfsnaam incorrect"
			       ,"020204" => "Admin contact: voorletter   incorrect"
			       ,"020205" => "Admin contact: tussenvoegsel incorrect"
			       ,"020206" => "Admin contact: Achternaam    incorrect"
			       ,"020207" => "Admin contact: Straatnaam    incorrect"
			       ,"020208" => "Admin contact: Huisnummer    incorrect"
			       ,"020209" => "Admin contact: Huisnummertoev incorrect"
			       ,"020210" => "Admin contact: Postcode       incorrect"
			       ,"020211" => "Admin contact: Plaatsnaam     incorrect"
			       ,"020212" => "Admin contact: Land           incorrect"
			       ,"020213" => "Admin contact: Emailadres     incorrect"
			       ,"020214" => "Admin contact: Telefoonnr     incorrect"
			       ,"020215" => "Admin contact: Aanvraagtype   incorrect"
			       ,"020216" => "Admin contact: Rechtsvorm     incorrect"
			       ,"020217" => "Admin contact: Registratienr  incorrect"
			       ,"020218" => "Admin contact: Registratienr  verplicht"
			       ,"020301" => "Tech contact: Ongeldig (id)"
			       ,"020302" => "Tech contact: Ongeldig (not allowed)"
			       ,"020303" => "Tech contact: bedrijfsnaam incorrect"
			       ,"020304" => "Tech contact: voorletter   incorrect"
			       ,"020305" => "Tech contact: tussenvoegsel incorrect"
			       ,"020306" => "Tech contact: Achternaam    incorrect"
			       ,"020307" => "Tech contact: Straatnaam    incorrect"
			       ,"020308" => "Tech contact: Huisnummer    incorrect"
			       ,"020309" => "Tech contact: Huisnummertoev incorrect"
			       ,"020310" => "Tech contact: Postcode       incorrect"
			       ,"020311" => "Tech contact: Plaatsnaam     incorrect"
			       ,"020312" => "Tech contact: Land           incorrect"
			       ,"020313" => "Tech contact: Emailadres     incorrect"
			       ,"020314" => "Tech contact: Telefoonnr     incorrect"
			       ,"020315" => "Tech contact: Aanvraagtype   incorrect"
			       ,"020316" => "Tech contact: Rechtsvorm     incorrect"
			       ,"020317" => "Tech contact: Registratienr  incorrect"
			       ,"020318" => "Tech contact: Registratienr  verplicht"
			       ,"020401" => "Billing: Ongeldig (id)"
			       ,"020402" => "Billing: Ongeldig (not allowed)"
			       ,"020403" => "Billing: bedrijfsnaam incorrect"
			       ,"020404" => "Billing: voorletter   incorrect"
			       ,"020405" => "Billing: tussenvoegsel incorrect"
			       ,"020406" => "Billing: Achternaam    incorrect"
			       ,"020407" => "Billing: Straatnaam    incorrect"
			       ,"020408" => "Billing: Huisnummer    incorrect"
			       ,"020409" => "Billing: Huisnummertoev incorrect"
			       ,"020410" => "Billing: Postcode       incorrect"
			       ,"020411" => "Billing: Plaatsnaam     incorrect"
			       ,"020412" => "Billing: Land           incorrect"
			       ,"020413" => "Billing: Emailadres     incorrect"
			       ,"020414" => "Billing: Telefoonnr     incorrect"
			       ,"020415" => "Billing: Aanvraagtype   incorrect"
			       ,"020416" => "Billing: Rechtsvorm     incorrect"
			       ,"020417" => "Billing: Registratienr  incorrect"
			       ,"020418" => "Billing: Registratienr  verplicht"
			       ,"020501" => "Domicilie: Ongeldig (id)"
			       ,"020502" => "Domicilie: Ongeldig (not allowed)"
			       ,"020503" => "Domicilie: bedrijfsnaam incorrect"
			       ,"020504" => "Domicilie: voorletter   incorrect"
			       ,"020505" => "Domicilie: tussenvoegsel incorrect"
			       ,"020506" => "Domicilie: Achternaam    incorrect"
			       ,"020507" => "Domicilie: Straatnaam    incorrect"
			       ,"020508" => "Domicilie: Huisnummer    incorrect"
			       ,"020509" => "Domicilie: Huisnummertoev incorrect"
			       ,"020510" => "Domicilie: Postcode       incorrect"
			       ,"020511" => "Domicilie: Plaatsnaam     incorrect"
			       ,"020512" => "Domicilie: Land           incorrect"
			       ,"020513" => "Domicilie: Emailadres     incorrect"
			       ,"020514" => "Domicilie: Telefoonnr     incorrect"
			       ,"020515" => "Domicilie: Aanvraagtype   incorrect"
			       ,"020516" => "Domicilie: Rechtsvorm     incorrect"
			       ,"020517" => "Domicilie: Registratienr  incorrect"
			       ,"020518" => "Domicilie: Registratienr  verplicht"
			       ,"020601" => "Contactsoort probleem"
			       ,"020701" => "Contact Sorteerprobleem"
			       ,"020702" => "Contact Order probleem"
			       ,"030101" => "Nameserver: incorrect (id)"
			       ,"030102" => "Nameservercheck failed"
			       ,"030103" => "Nameserver: ongeldige waarde auto ophalen"
			       ,"030201" => "NS1 incorrect"
			       ,"030202" => "NS1 bestaat niet"
			       ,"030203" => "NS1: ongeldig ip"
			       ,"030206" => "NS2 incorrect"
			       ,"030207" => "NS2 bestaat niet"
			       ,"030208" => "NS2: ongeldig ip"
			       ,"030301" => "Nameserver Sorteerprobleem"
			       ,"030302" => "Nameserver Order probleem"
			       ,"040001" => "Type incorrect"
			       ,"040002" => "Domeinname incorrect"
			       ,"040003" => "TLD (domeinextensie) incorrect"
                               );
		return $errors[$i];
	}
}

?>
