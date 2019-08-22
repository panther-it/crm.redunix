<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlDevices extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","customer",$constraint);


                switch ($viewType)
		{
		        case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id      =  '$1' "
                                     . "OR name  like '%$1%' "
                                     . "OR label like '%$1%' "
                                     . "OR brand like '%$1%')"
				     , $constraint);
 			case Settings::ASFORM:
 			case Settings::ASGRID:
                        	return "SELECT id
                                             , customer
                                             , name
                                             , rack
                                             , position 
                                             , label
                                             , brand
                                             , type 
                                          FROM devices 
                                         WHERE $constraint
                                      ORDER BY rack
                                             , position";
                	case Settings::ASLIST:
                        case Settings::ASCREATELIST:
                        	return "SELECT id
                                             , label 
                                          FROM devices 
                                         WHERE $constraint
                                      ORDER BY label";
                	default:
                        	return "Unknown viewType";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                //Default values
                if (!isset($values["label"]) && isset($values["name"] )) $values["label"] = $values["name"] ; //. "." . $values["customer"];
                if (!isset($values["name"] ) && isset($values["label"])) $values["name"]  = $values["label"];

                $sql = "INSERT INTO devices "
                     . ((isset($values["customer"] ) && !empty($values["customer"])) ? ", customer  " : "")
                     . ((isset($values["name"]     ) && !empty($values["name"]    )) ? ", name      " : "")
                     . ((isset($values["label"]    ) && !empty($values["label"]   )) ? ", label     " : "")
                     . ((isset($values["rack"]     ) && !empty($values["rack"]    )) ? ", rack      " : "")
                     . ((isset($values["position"] ) && !empty($values["position"])) ? ", position  " : "")
                     . ((isset($values["brand"]    ) && !empty($values["brand"]   )) ? ", brand     " : "")
                     . ((isset($values["type"]     ) && !empty($values["type"]    )) ? ", type      " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["customer"] ) && !empty($values["customer"])) ? ",'" . mysql_escape_string($values["customer"]) . "'" : "")
                     . ((isset($values["name"]     ) && !empty($values["name"]    )) ? ",'" . mysql_escape_string($values["name"]    ) . "'" : "")
                     . ((isset($values["label"]    ) && !empty($values["label"]   )) ? ",'" . mysql_escape_string($values["label"]   ) . "'" : "")
                     . ((isset($values["rack"]     ) && !empty($values["rack"]    )) ? ",'" . mysql_escape_string($values["rack"]    ) . "'" : "")
                     . ((isset($values["position"] ) && !empty($values["position"])) ? ",'" . mysql_escape_string($values["position"]) . "'" : "")
                     . ((isset($values["brand"]    ) && !empty($values["brand"]   )) ? ",'" . mysql_escape_string($values["brand"]   ) . "'" : "")
                     . ((isset($values["type"]     ) && !empty($values["type"]    )) ? ",'" . mysql_escape_string($values["type"]    ) . "'" : "")
                     . "          ) ";
                $sql = preg_replace("/, /","( ",$sql,1);
                $sql = preg_replace("/,'/","('",$sql,1);
                $values["id"] = $database->mutate($sql);
		$status       = $values["id"];
                return $sql . "\n" . $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE devices "
                     . ((isset($values["customer"]) && !empty($values["customer"])) ? ", customer = '" . mysql_escape_string($values["customer"]) . "'" : "")
                     . ((isset($values["name"]    ) && !empty($values["name"]    )) ? ", name     = '" . mysql_escape_string($values["name"]    ) . "'" : "")
                     . ((isset($values["label"]   ) && !empty($values["label"]   )) ? ", label    = '" . mysql_escape_string($values["label"]   ) . "'" : "")
                     . ((isset($values["rack"]    ) && !empty($values["rack"]    )) ? ", rack     = '" . mysql_escape_string($values["rack"]    ) . "'" : "")
                     . ((isset($values["position"]) && ($values["position"] != "")) ? ", position = '" . mysql_escape_string($values["position"]) . "'" : "")
                     . ((isset($values["brand"]   ) && !empty($values["brand"]   )) ? ", brand    = '" . mysql_escape_string($values["brand"]   ) . "'" : "")
                     . ((isset($values["type"]    ) && !empty($values["type"]    )) ? ", type     = '" . mysql_escape_string($values["type"]    ) . "'" : "")
                                                                                    . "  WHERE id = '" . mysql_escape_string($values["id"]      ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  devices WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
