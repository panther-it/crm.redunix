<?
require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/coloAccess.php");
require_once(__DIR__ . "/sql/sqlcontacts.php"); 
require_once('/var/www/crm/classes/api/grafix.php');

class Grafix extends ColoAccess
{

        function __construct() 
        {
        }


        public static function CreateContact($contact)
        {

                //global $database;

		//--get contact--
                //$rs   = $database->query(SqlContacts::query(Settings::ASGRID,"c.id=" . $contactId));
		//if (!is_resource($rs)       ) return $rs; //error
                //if (mysql_num_rows($rs) == 0) return "Contact '" . $contactId . "' not found";
                //$contact  = mysql_fetch_array($rs);

		if (!self::FindContact($contact))
                {
			//--insert into grafix noc db--
			$result = self::execute("createcontact",$contact);

        		if (is_array($result) && $result['is_success'])
	        	{
				//--update crm db--
                        	$contact->grafix_id = $result["RETtext"];
                        	echo SQLContacts::update($contact);
				//TODO: email toegang@grafix.nl met nieuwe contact:pas koppeling
                        	return $contact->grafix_id;
                	}
                	else
			{
				if (is_array($result)) $result = $result['RETcode'] . ": " . $result['RETtext'];
        	                return "Create GrafiX Contact mislukt: " . $result . ".<BR/>\n";
			}
		}
		else
		{
			$result = self::UpdateContact($contact);
                	return "GrafiX Contact already exists: " . $result . ".<BR/>";
		}
        }


	public static function UpdateContact($contact)
	{
		//--update grafix db--
		$result = self::execute("updatecontact",$contact);
		if (is_array($result)) $result = $result['RETcode'] . ": " . $result['RETtext'];
		return $result;
		//self::DeleteContact($contact);
		//self::CreateContact($contact);
 	}

	public static function DeleteContact($contact)
	{
		//--remove from grafix db--
		$result = self::execute("deletecontact",$contact);
		if (is_array($result)) $result = $result['RETcode'] . ": " . $result['RETtext'];

		//--remove grafix reference in crm db--
		$contact->grafix_id = "";
		echo SqlContacts::update($contact);
		return $result;

 	}


	private static function execute($command,&$contact)
	{
		if (!is_object($contact)) //'id' given instead of full row object
			$contact  = SqlContacts::find("c.id=" . $contact);
		if (!is_object($contact))
			return "Unable to query Contact details: " . $contact;

		$cmd                 = array();
                $cmd['command']      = $command;
                $cmd['contactname']  = $contact->firstname . " " . $contact->lastname . " - " . $contact->organization_name;
                $cmd['contactmail']  = $contact->email;
                $cmd['contactphone'] = $contact->phone_mobile;
        	$cmd['contactid']    = $contact->grafix_id;

		//--sanity checks--
		if (empty($cmd['contactphone']  )) $cmd['contactphone'] = $contact->phone; 
		$cmd['contactphone'] = preg_replace(array("/[ \.\-]/","/^\+/"), array("","00"), $cmd['contactphone']); //strict syntax rules by grafix
		if ($cmd['contactname' ] == " - ") return "Unknown Contact '" . $contact->id . "'";
		if (empty($cmd['contactmail' ]  )) return "Missing contact field 'email'";
		if (empty($cmd['contactphone']  )) return "Missing contact field 'phone'";

        	$grafix              = new GrafixTransaction($cmd);
		//print_r($grafix);
		var_dump(get_object_vars($grafix));
		return $grafix->values;
	}


	//Currently FindContact will alway fail because grafix function 'iscontact' has been removed from the API
	public static function FindContact(&$contact)
	{
		//--check grafix noc db for existing contact--
		$result    = self::execute("iscontact",$contact);
                $grafix_id = trim(str_replace("Contactid for this contact is","",$result["RETtext"]));

		if (empty($grafix_id) || ($grafix_id == "Contact not found")) 
			return false; 
		else
		{
			$contact->grafix_id = $grafix_id;
                        echo SQLContacts::update($contact);
		}

		return true;
 	}


	public static function visit($values)
	{
		global $auth;
	
		$cmd              = array();
	       	$cmd['command']   = "createvisit";
	        $cmd['contactid'] = $values["contact"]; 
	        $cmd['cardid']    = $values["access_ids"];
	        $cmd['date']      = $values["day"]  . $values["month"] . $values["year"];
	        $cmd['time']      = $values["hour"] . '' . $values["minute"];
	        $cmd['worktime']  = $values["duration"];
	        $cmd['nocid']     = $values["datacenter"]; //1=cpl,2=sk,3=tc2,4=redbus,5=alkmaar,6=alphen //1,7=alphen2
	        $cmd['note']      = $values["reason"];
	
	        $grafix = new GrafixTransaction($cmd);

	        if ($grafix->values['is_success'])
	        {
	                parent::mailConfirm($values,$grafix->values);
	                return "Aanmelding geslaagd.<BR/>Uw toegangscode is " . $grafix->values['NOCcode'] . ".<BR/>Zie ook uw emailbox " . $auth->contact->email . ".";
	        }
	        else
	        {
	                //print("<pre>");
			//print_r($cmd);
	                //print_r($grafix);
			//print("</pre>");
			var_dump(get_object_vars($grafix));
	                return "Aanmelding mislukt.<BR/>" . $grafix->values['RETcode'] . ": " . $grafix->values['RETtext'];
	        }

	}
}
?>
