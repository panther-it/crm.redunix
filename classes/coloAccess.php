<?
require_once __DIR__ . "/settings.php";
require_once __DIR__ . "/sql/sqlcoloaccess.php";

class ColoAccess
{

        function __construct() 
        {
        }

	public static function mailConfirm($request,$response)
	{
		global $auth;
                $timeStart = $request["year"] . "-" . $request["month"] . "-" . $request["day"] . " " . $request["hour"] . ":" . $request["minute"];

	
	        mail($auth->contact->email
	            ,"Aanmelding bezoek DataCenter @ " . $timeStart
	            ,"=====================================          \n"
	            ."Aanmelding bezoek DataCenter                   \n"
	            ."=====================================          \n"
	            ."Datum       : " . $timeStart               .  "\n"  
	            ."Naam        : " . $auth->customer->name    .  "\n"
	            ."Bedrijfsnaam: " . $auth->customer->company .  "\n"
	            ."Reden       : " . $request["reason"]       .  "\n"
	            ."Toegangspas : " . $request["access_ids"]   .  "\n"
	            ."Locatie     : " . $request["datacenter"]   .  "\n"
	            ."Status      : ACCEPTED                         \n"
	            ."ID          : " . $response["NOCcode"]     .  "\n"
	            ."=====================================          \n"
	            .$response["RETcode"] . $response["RETtext"] . ".\n"
	            ."=====================================          \n"
	            ,"From: REDUNIX - Internet Diensten <info@redunix.nl>\nBCC: hostmaster@redunix.nl");
	}

	public static function validKeyCard($access_ids)
	{
		global $auth;
		global $database;

	        $rs        = $database->query(SqlColoAccess::query(Settings::ASGRID, "ca.customer=" . $auth->customer->id . " AND ad.accesstype='card'")); // AND datacenters.id=" . $gfx_dc_id
		$valid_ids = array();

	        while($r = mysql_fetch_array($rs))
			array_push($valid_ids,$r[0]);

		if (is_array($access_ids))
		{
			$new_ids = array();
			foreach($access_ids as $id)
				if (in_array($id,$valid_ids)) 
					array_push($new_ids,$id);
			$access_ids = $new_ids;
			if (empty($access_ids))
				return FALSE;
			else
				return TRUE;
		}	
		else if (is_string($access_ids))
			if (in_array($access_ids,$valid_ids))
				return TRUE;
			else
				return FALSE;
		return FALSE;	
	}


	public static function getKeyCardIdsAsCheckBoxes()
	{
		global $auth;
	        global $database;

	        $rs        = $database->query(SqlColoAccess::query(Settings::ASLIST, "ca.customer=" . $auth->customer->id . " AND ad.accesstype='card'")); // AND datacenters.id=" . $gfx_dc_id
	        $html = "";
	        while($r = mysql_fetch_array($rs))
	                $html .= "<INPUT TYPE=\"checkbox\" NAME=\"access_ids\" CHECKED value=\"" . $r[0] . "\"/>" . $r[1] . "<BR/>\n";
	        return $html;
	}

}
?>
