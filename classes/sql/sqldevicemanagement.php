<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlDeviceManagement extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","d.customer",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  da.id      =  '$1' "
                                     . "OR da.ip   like '%$1%') "
                                     . "OR d.label like '%$1%') "
				     , $constraint);
		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT dm.id
                                     , device_id as device
                                     , dm.type
                                     , ip 
                                     , username
                                     , password
                                  FROM device_management dm
                                     , devices d
                                 WHERE $constraint
                                   AND dm.device_id = d.id
                              ORDER BY ip";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT dm.id                  AS id
                                     , CONCAT(d.label, ' > '
                                             ,dm.type)        AS label
                                   FROM device_management dm
                                      , devices d
                                  WHERE dm.device_id = d.id
                                    AND $constraint
                               ORDER BY label";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO device_management "
                     . ((isset($values["device"   ]) && !empty($values["device"   ])) ? ", device_id  " : "")
                     . ((isset($values["type"     ]) && !empty($values["type"     ])) ? ", type       " : "")
                     . ((isset($values["ip"       ]) && !empty($values["ip"       ])) ? ", ip         " : "")
                     . ((isset($values["username" ]) && !empty($values["username" ])) ? ", username   " : "")
                     . ((isset($values["password" ]) && !empty($values["password" ])) ? ", password   " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["device"   ]) && !empty($values["device"   ])) ? ",'" . mysql_escape_string($values["device"   ]) . "'" : "")
                     . ((isset($values["type"     ]) && !empty($values["type"     ])) ? ",'" . mysql_escape_string($values["type"     ]) . "'" : "")
                     . ((isset($values["ip"       ]) && !empty($values["ip"       ])) ? ",'" . mysql_escape_string($values["ip"       ]) . "'" : "")
                     . ((isset($values["username" ]) && !empty($values["username" ])) ? ",'" . mysql_escape_string($values["username" ]) . "'" : "")
                     . ((isset($values["password" ]) && !empty($values["password" ])) ? ",'" . mysql_escape_string($values["password" ]) . "'" : "")
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
                $sql = "UPDATE device_management "
                     . ((isset($values["device"   ]) && !empty($values["device"   ])) ? ", device_id = '" . mysql_escape_string($values["device"   ]) . "'" : "")
                     . ((isset($values["type"     ]) && !empty($values["type"     ])) ? ", type      = '" . mysql_escape_string($values["type"     ]) . "'" : "")
                     . ((isset($values["ip"       ]) && !empty($values["ip"       ])) ? ", ip        = '" . mysql_escape_string($values["ip"       ]) . "'" : "")
                     . ((isset($values["username" ]) && !empty($values["username" ])) ? ", username  = '" . mysql_escape_string($values["username" ]) . "'" : "")
                     . ((isset($values["password" ]) && !empty($values["password" ])) ? ", password  = '" . mysql_escape_string($values["password" ]) . "'" : "")
                                                                                      . "  WHERE id  = '" . mysql_escape_string($values["id"]       ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  device_management WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
