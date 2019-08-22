<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../databaseUnit4.php";
require_once __DIR__ . "/sqlcommon.php";
require_once __DIR__ . "/../settings.php";

class SqlCustomers extends SqlCommon
{
        public static function query($viewType,$constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","id",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id      =  '$1' "
                                     . "OR name like '%$1%') "
				     , $constraint);
		    case Settings::ASGRID:
                        return "SELECT id           "
                              ."     , organization "
                              ."     , name         "
                              ."     , state        "
                              ."  FROM customers    "
                              ." WHERE $constraint  "
                              ." ORDER BY name      "; 
		    case Settings::ASFORM:
                        return "SELECT id           "
                              ."     , organization "
                              ."     , name         "
                              ."     , state        "
                              ."     , bank_account "
                              ."  FROM customers    "
                              ." WHERE $constraint ";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id           "
                              ."     , name         " 
                              ."  FROM customers    "
                              ." WHERE $constraint  "
                              ." ORDER BY name      ";
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
                        return "SELECT contacts.id
                                     , concat(accessdevices.accesstype , ' '
                                     , accessdevices.accessid, ' - '
                                     , contacts.firstname, ' '
                                     , contacts.lastname, ' @ '
                                     , suites.name, ' te ', datacenters.name  ) AS description
                                     , accessdevices.accessid
                                  FROM colo_access
                                     , racks
                                     , accessdevices 
				     , suites
				     , datacenters
                                     , contacts
                                 WHERE colo_access.rack         = racks.id 
                                   AND colo_access.accessdevice = accessdevices.id 
				   AND racks.suite              = suites.id
                                   AND suites.datacenter        = datacenters.id
                                   AND colo_access.customer = '" . $customerId . "' 
                                   AND contacts.owner       = '" . $customerId . "' 
                                   AND colo_access.contact  = contacts.id
                                   AND $constraint 
                              ORDER BY contacts.id 
                               ";
        }

/*
	public static function get($constraint)
	{
		global $database;

		if (is_array($constraint))
			$constraint = $database->buildConstraint($contraint);
		else
			if (!strpos($constraint,"="))
				$constraint = "id = " . $constraint;

		$device = $database->fetchObject(self::query(Settings::ASFORM,$constraint));
		return $device;
	}

	public static function find($value)
	{
		global $database;
		return $database->fetchObject(self::query(Settings::ASSEARCH,$value));
	}

	public static function findIds($value)
	{
		global $database;
		return $database->fetchIds(self::query(Settings::ASSEARCH,$value));
	}
*/

	public static function access($customerId)
	{
		global $database;
		$sql = self::query(Settings::ASFORM,"id= $customerId");
		$rs  = $database->query($sql);
		return mysql_num_rows($rs) > 0;
	}


        public static function insert(&$values)
        {
                global $database;
                global $databaseUnit4;

		parent::insert($values);

                $sql = "INSERT INTO customers "
                     . ((isset($values["organization"]) && !empty($values["organization"])) ? ", organization  " : "")
                     . ((isset($values["name"]        ) && !empty($values["name"]    	 )) ? ", name     " : "")
                     . ((isset($values["state"]       ) && !empty($values["state"]   	 )) ? ", state    " : "")
                     . ((isset($values["bank_account"]) && !empty($values["bank_account"])) ? ", bank_account    " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["organization"]) && !empty($values["organization"])) ? ",'" . mysql_escape_string($values["organization"]) . "'": "")
                     . ((isset($values["name"]        ) && !empty($values["name"]    	 )) ? ",'" . mysql_escape_string($values["name"]        ) . "'": "")
                     . ((isset($values["state"]       ) && !empty($values["state"]   	 )) ? ",'" . mysql_escape_string($values["state"]       ) . "'": "")
                     . ((isset($values["bank_account"]) && !empty($values["bank_account"])) ? ",'" . mysql_escape_string($values["bank_account"]) . "'": "")
                     . "          ) ";
                $sql = preg_replace("/, /", "( ", $sql, 1);
                $sql = preg_replace("/,'/", "('", $sql, 1);
                #print($sql);
                $values["id"] = $database->mutate($sql);
		$status       = $databaseUnit4->mutate($values);
                return $status;
        }


        public static function update($values)
        {
                global $database;
                global $databaseUnit4;

                parent::update($values);
                
                $sql = "UPDATE customers "
                     . ((isset($values["organization"]) && !empty($values["organization"])) ? ", organization = '" . mysql_escape_string($values["organization"]) . "'" : "")
                     . ((isset($values["name"]        ) && !empty($values["name"]        )) ? ", name         = '" . mysql_escape_string($values["name"]        ) . "'" : "")
                     . ((isset($values["bank_account"]) && !empty($values["bank_account"])) ? ", bank_account = '" . mysql_escape_string($values["bank_account"]) . "'" : "")
                     . ((isset($values["state"]       ) && !empty($values["state"]       )) ? ", state        = '" . mysql_escape_string($values["state"]       ) . "'" : "")
                                                                                            . "  WHERE id     = '" . mysql_escape_string($values["id"]          ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                #print($sql);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                global $databaseUnit4;
                $sql = "DELETE FROM  customers WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                #print($sql);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $status;
        }
}

?>
