<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlDatacenters extends SqlCommon
{
        public static function query($viewType, $constraint="1=1")
        {
		global $auth;
		parent::query($viewType, $constraint);
                if ($auth->getLevel("datacenters") != Authorization::ADMIN_LEVEL) return "Error: Not authorized"; 

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id      =  '$1' "
                                     . "OR name like '%$1%') "
				     , $constraint);
		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT id 
                                     , name
                                     , contact
                                     , address
                                     , accesstype
                                  FROM datacenters
                                 WHERE $constraint 
                              ORDER BY name";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id, name FROM datacenters ORDER BY name";
	            default:
                        return "Unknown viewType";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO datacenters "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ", name       " : "")
                     . ((isset($values["contact"]   ) && !empty($values["contact"]   )) ? ", contact    " : "")
                     . ((isset($values["coords"]    ) && !empty($values["coords"]    )) ? ", coords     " : "")
                     . ((isset($values["address"]   ) && !empty($values["address"]   )) ? ", address    " : "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? ", accesstype " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ",'" . mysql_escape_string($values["name"]      ) . "'" : "")
                     . ((isset($values["contact"]   ) && !empty($values["contact"]   )) ? ",'" . mysql_escape_string($values["contact"]   ) . "'" : "")
                     . ((isset($values["coords"]    ) && !empty($values["coords"]    )) ? ",'" . mysql_escape_string($values["coords"]    ) . "'" : "")
                     . ((isset($values["address"]   ) && !empty($values["address"]   )) ? ",'" . mysql_escape_string($values["address"]   ) . "'" : "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? ",'" . mysql_escape_string($values["accesstype"]) . "'" : "")
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
                $sql = "UPDATE datacenters "
                     . ((isset($values["name"]      ) && !empty($values["name"]      )) ? ", name       = '" . mysql_escape_string($values["name"]      ) . "'" : "")
                     . ((isset($values["contact"]   ) && !empty($values["datacenter"])) ? ", datacenter = '" . mysql_escape_string($values["datacenter"]) . "'" : "")
                     . ((isset($values["coords"]    ) && !empty($values["coords"]    )) ? ", coords     = '" . mysql_escape_string($values["coords"]    ) . "'" : "")
                     . ((isset($values["address"]   ) && !empty($values["address"]   )) ? ", address    = '" . mysql_escape_string($values["address"]   ) . "'" : "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? ", accesstype = '" . mysql_escape_string($values["accesstype"]) . "'" : "")
                                                                                        . "  WHERE id   = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  datacenters WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
