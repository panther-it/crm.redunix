<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlSuites extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		global $auth;
		parent::query($viewType, $constraint);
                if ($auth->getLevel("suites") != Authorization::ADMIN_LEVEL) return "Error: Not Authorized"; 

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id      =  '$1' "
                                     . "OR name like '%$1%') "
				     , $constraint);
		    case Settings::ASGRID:
		    case Settings::ASFORM:
                        return "SELECT id, name, datacenter, floor FROM suites WHERE $constraint ORDER BY datacenter, floor";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id, name FROM suites ORDER BY name";
		}
        }

	public static function access($customerId)
	{
		return SqlDevices::access($customerId); 
	}


        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO suites "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ", name      " : "")
                     . ((isset($values["datacenter"]) && !empty($values["datacenter"])) ? ", datacenter      " : "")
                     . ((isset($values["floor"]     ) && !empty($values["floor"]     )) ? ", floor  " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ",'" . mysql_escape_string($values["name"]      ) . "'" : "")
                     . ((isset($values["datacenter"]) && !empty($values["datacenter"])) ? ",'" . mysql_escape_string($values["datacenter"]) . "'" : "")
                     . ((isset($values["floor"]     ) && !empty($values["floor"]     )) ? ",'" . mysql_escape_string($values["floor"]     ) . "'" : "")
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
                $sql = "UPDATE suites "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ", name       = '" . mysql_escape_string($values["name"]      ) . "'" : "")
                     . ((isset($values["datacenter"]) && !empty($values["datacenter"])) ? ", datacenter = '" . mysql_escape_string($values["datacenter"]) . "'" : "")
                     . ((isset($values["floor"]     ) && !empty($values["floor"]     )) ? ", floor      = '" . mysql_escape_string($values["floor"]     ) . "'" : "")
                                                                                        . "  WHERE id   = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  suites WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
