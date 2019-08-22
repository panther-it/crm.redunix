<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../settings.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlAccessDevices extends SqlCommon
{
         public static function query($viewType,$constraint = '1=1')
        {
		global $auth;
		parent::query($viewType, $constraint);
                if ($auth->getLevel("accessdevices") != Authorization::ADMIN_LEVEL) return "Error: Not authorized";

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id       = '$1' "
                                     . "OR accessid = '$1') "
				     , $constraint);
		    case Settings::ASGRID:
		    case Settings::ASFORM:
                        return "SELECT * FROM accessdevices WHERE $constraint";
                    case Settings::ASLIST:
                        return "SELECT id
                                     , CONCAT(accesstype, ' ', accessid)       AS accessid 
                                  FROM accessdevices            
                                 WHERE $constraint
                              ORDER BY accesstype, accessid";
                    case Settings::ASCREATELIST:
                        return "SELECT ad.id                                   AS id
                                     , CONCAT(ad.accesstype, ' ', ad.accessid) AS accessid 
                                  FROM accessdevices ad
                       LEFT OUTER JOIN colo_access   ca
                                    ON ca.accessdevice  = ad.id
                                 WHERE ca.id           is null             
                                   AND $constraint
                              ORDER BY ad.accesstype, ad.accessid";
                }
        }


	public static function access($customerId)
	{
		return false; 
	}

        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO accessdevices "
                     . "          ( accessid      "
                     . "          , accesstype    "
                     . "          , suite         "
                     . "          )               "
                     . "     VALUES               "
                     . "          ('" . mysql_escape_string($values["accessid"]  ) . "'"
                     . "          ,'" . mysql_escape_string($values["accesstype"]) . "'"
                     . "          ,'" . mysql_escape_string($values["suite"]     ) . "'"
                     . "          ) ";
                print($sql);
                $values["id"] = $database->mutate($sql);
                $status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE accessdevices "
                     . ((isset($values["accessid"])   && !empty($values["accessid"]  )) ? "     , accessid   = '" . mysql_escape_string($values["accessid"]  ) . "'": "")
                     . ((isset($values["accesstype"]) && !empty($values["accesstype"])) ? "     , accesstype = '" . mysql_escape_string($values["accesstype"]) . "'": "")
                     . ((isset($values["suite"])      && !empty($values["suite"]     )) ? "     , suite      = '" . mysql_escape_string($values["suite"]     ) . "'": "")
                                                                                        . " WHERE id         = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  accessdevices WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
