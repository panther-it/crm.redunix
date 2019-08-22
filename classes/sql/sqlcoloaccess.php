<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlColoAccess extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);

		$constraint = str_replace("owner","ca.customer",$constraint);
		$constraint = preg_replace("/ id = /i"," ca.id = ",$constraint); //ambiguos

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  ca.id     =  '$1' "
                                     . "OR s.name like '%$1%') "
				     , $constraint);
		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT ca.id
                                     , ca.rack
				     , ca.customer
				     , ca.contact 
				     , ca.accessdevice 
                                     , ad.accessid   AS accessid
                                     , dc.name       AS datacenter_name
                                     , ad.accesstype AS accesstype 
                                  FROM colo_access   ca
                                     , accessdevices ad
                                     , customers     c
                                     , contacts      co
                                     , racks         r
                                     , suites        s
                                     , datacenters   dc
                                 WHERE ca.customer     = c.id
                                   AND ca.contact      = co.id 
                                   AND ca.accessdevice = ad.id
                                   AND ca.rack         = r.id
                                   AND r.suite         = s.id
                                   AND s.datacenter    = dc.id 
				   AND $constraint
			      ORDER BY r.name DESC
				     , c.name
				     , co.firstname
                                     , ad.accessid";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT ca.id
                                     , CONCAT(c.name         , ' - '
                                             ,co.firstname   , ' '
                                             ,co.lastname    , ': '
                                             ,ad.accesstype  , ' '
                                             ,ad.accessid
                                             )               AS access
                                  FROM colo_access   ca
                                     , accessdevices ad
                                     , customers     c
                                     , contacts      co
                                     , racks         r
                                     , suites        s
                                     , datacenters   dc
                                 WHERE ca.customer     = c.id
                                   AND ca.contact      = co.id 
                                   AND ca.accessdevice = ad.id
                                   AND ca.rack         = r.id
                                   AND r.suite         = s.id
                                   AND s.datacenter    = dc.id 
				   AND $constraint
			      ORDER BY r.name
				     , c.name
				     , co.firstname
                                     , ad.accessid";
 		}
        }

        public static function queryAccessDevices($customerId, $constraint, $viewType)
        {
                if (empty($constraint)) $constraint = "1=1";
                if ($viewType == Settings::ASGRID)
                        return "SELECT racks.id                 AS rack_id
                                     , racks.name               AS rack_name
                                     , accessdevices.id         AS access_id
                                     , accessdevices.accessid   AS access_code
                                     , accessdevices.accesstype AS access_type
                                  FROM colo_access
                                     , racks
                                     , accessdevices 
                                     , suites
                                     , datacenters
                                 WHERE colo_access.rack         = racks.id 
                                   AND colo_access.accessdevice = accessdevices.id 
				   AND racks.suite              = suites.id
                                   AND suites.datacenter        = datacenters.id
                                   AND customer = '" . $customerId . "' 
                                   AND $constraint 
                              ORDER BY rack_id
                                     , access_type
                                     , access_code
                               ";
                else if ($viewType == Settings::ASLIST)
                        return "SELECT accessdevices.accessid    AS access_code
                                     , concat(accessdevices.accesstype , ' '
                                     , accessdevices.accessid  ) AS description
                                  FROM colo_access
                                     , racks
                                     , accessdevices 
				     , suites
				     , datacenters
                                 WHERE colo_access.rack         = racks.id 
                                   AND colo_access.accessdevice = accessdevices.id 
				   AND racks.suite              = suites.id
                                   AND suites.datacenter        = datacenters.id
                                   AND customer = '" . $customerId . "' 
                                   AND $constraint 
                              ORDER BY access_code
                               ";
        }


	/* find one contact based on a generic search string */
        /* used in the grid filterRow                        */
	public static function findByContact($contactid, $accessid = NULL)
	{
		//global $database;
		$constraint = "(  co.id   = '" . mysql_escape_string($contactid) . "' "
                            . (!empty($accessid) ? "AND accessid = '" . mysql_escape_string($accessid) . "' " : "")
                            . ")";
		return self::find($constraint); //$contact;
	}


        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO colo_access "
                     . "          ( rack          "
                     . "          , customer      "
                     . "          , contact       "
                     . "          , accessdevice  "
                     . "          )               "
                     . "     VALUES               "
                     . "          ('" . mysql_escape_string($values["rack"]        ) . "'"
                     . "          ,'" . mysql_escape_string($values["customer"]    ) . "'"
                     . "          ,'" . mysql_escape_string($values["contact"]     ) . "'"
                     . "          ,'" . mysql_escape_string($values["accessdevice"]) . "'"
                     . "          ) ";
                print($sql);
                $values["id"] = $database->mutate($sql);
                $status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE colo_access "
                     . ((isset($values["rack"]        ) && !empty($values["rack"]        )) ? "     , rack         = '" . mysql_escape_string($values["rack"]        ) . "'": "")
                     . ((isset($values["customer"]    ) && !empty($values["customer"]    )) ? "     , customer     = '" . mysql_escape_string($values["customer"]    ) . "'": "")
                     . ((isset($values["contact"]     ) && !empty($values["contact"]     )) ? "     , contact      = '" . mysql_escape_string($values["contact"]     ) . "'": "")
                     . ((isset($values["accessdevice"]) && !empty($values["accessdevice"])) ? "     , accessdevice = '" . mysql_escape_string($values["accessdevice"]) . "'": "")
                                                                                            . " WHERE id           = '" . mysql_escape_string($values["id"]          ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  colo_access WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
