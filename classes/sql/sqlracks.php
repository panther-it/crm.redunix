<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlRacks extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		global $auth;
		parent::query($viewType, $constraint);
                if ($auth->getLevel("racks") != Authorization::ADMIN_LEVEL) return "Error: Not Authorized"; 

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  r.id      =  '$1' "
                                     . "OR r.name like '%$1%') "
				     , $constraint);
		    case Settings::ASGRID:
		    case Settings::ASFORM:
                        return "SELECT id, name, suite, accesstype FROM racks WHERE $constraint ORDER BY suite, accesstype";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT r.id
                                     , r.name
                                   FROM racks r
                                      , suites s
                                      , datacenters d
                                  WHERE r.suite      = s.id
                                    AND s.datacenter = d.id
                               ORDER BY name";
                               //      , CONCAT(d.name, ' > '
                               //              ,s.name, ' > '
                               //              ,r.name) AS name
		}
        }

	public static function access($customerId)
	{
		return SqlDevices::access($customerId); 
	}


        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO racks "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ", name        " : "")
                     . ((isset($values["suite"]     ) && !empty($values["suite"]     )) ? ", suite       " : "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? ", accesstype  " : "")
                     . ((isset($values["accesscode"]) && !empty($values["accesstype"])) ? ", accesstype  " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ",'" . mysql_escape_string($values["name"]      ) . "'" : "")
                     . ((isset($values["suite"]     ) && !empty($values["suite"]     )) ? ",'" . mysql_escape_string($values["suite"]     ) . "'" : "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? ",'" . mysql_escape_string($values["accesstype"]) . "'" : "")
                     . ((isset($values["accesscode"]) && !empty($values["accesscode"])) ? ",'" . mysql_escape_string($values["accesscode"]) . "'" : "")
                     . "          ) ";
                $sql = preg_replace("/, /","( ",$sql,1);
                $sql = preg_replace("/,'/","('",$sql,1);
                print($sql);
                $values["id"] = $database->mutate($sql);
		$status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE racks "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ", name       = '" . mysql_escape_string($values["name"]      ) . "'" : "")
                     . ((isset($values["suite"]     ) && !empty($values["suite"]     )) ? ", suite      = '" . mysql_escape_string($values["suite"]     ) . "'" : "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? ", accesstype = '" . mysql_escape_string($values["accesstype"]) . "'" : "")
                     . ((isset($values["accesscode"]) && !empty($values["accesscode"])) ? ", accesscode = '" . mysql_escape_string($values["accesscode"]) . "'" : "")
                                                                                        . "  WHERE id   = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  racks WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
