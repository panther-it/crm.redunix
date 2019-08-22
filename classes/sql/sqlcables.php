<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlCables extends SqlCommon
{
        public static function query($viewType,$constraint = "1=1")
        {
		//global $auth;
		parent::query($viewType, $constraint);
                //if ($auth->getLevel("cables") != Authorization::ADMIN_LEVEL) return "Error: Not authorized";

		switch($viewType)
		{
		    case Settings::ASSEARCH:
		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT id
                                     , deviceA_id
                                     , deviceA_port
                                     , deviceB_id
                                     , deviceB_port
                                     , cableType
                                  FROM cables
                                 WHERE $constraint
                              ORDER BY deviceA_id
                                     , deviceB_id
                                     , cableType
                                     , deviceA_port
                                     , deviceB_port";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT c.id                   AS id
                                     , CONCAT( da.name, ' > '
                                             , c.cableType, ' > '
                                             , db.name
                                             )                AS cable
                                  FROM cables
                                     , devices da
                                     , devices db
                                 WHERE cables.deviceA_id = da.id
                                   AND cables.deviceB_id = db.id
                                   AND $constraint
                              ORDER BY cable";
		}
        }


	public static function access($customerId)
	{
		return SqlDevices::access($customerId); 
	}

        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO cables        "
                     . "          ( deviceA_id    "
                     . "          , deviceA_port  "
                     . "          , deviceB_id    "
                     . "          , deviceB_port  "
                     . "          , cableType     "
                     . "          )               "
                     . "     VALUES               "
                     . "          ('" . mysql_escape_string($values["deviceA_id"]  ) . "'"
                     . "          ,'" . mysql_escape_string($values["deviceA_port"]) . "'"
                     . "          ,'" . mysql_escape_string($values["deviceB_id"]  ) . "'"
                     . "          ,'" . mysql_escape_string($values["deviceB_port"]) . "'"
                     . "          ,'" . mysql_escape_string($values["cableType"]   ) . "'"
                     . "          ) ";
                print($sql);
                $values["id"] = $database->mutate($sql);
                $status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE cables "
                     . ((isset($values["deviceA_id"]  ) && !empty($values["deviceA_id"]  )) ? "     , deviceA_id   = '" . mysql_escape_string($values["deviceA_id"]  ) . "'": "")
                     . ((isset($values["deviceA_port"]) && !empty($values["deviceA_port"])) ? "     , deviceA_port = '" . mysql_escape_string($values["deviceA_port"]) . "'": "")
                     . ((isset($values["deviceB_id"]  ) && !empty($values["deviceB_id"]  )) ? "     , deviceB_id   = '" . mysql_escape_string($values["deviceB_id"]  ) . "'": "")
                     . ((isset($values["deviceB_port"]) && !empty($values["deviceB_port"])) ? "     , deviceB_port = '" . mysql_escape_string($values["deviceB_port"]) . "'": "")
                     . ((isset($values["cableType"]   ) && !empty($values["cableType"]   )) ? "     , cableType    = '" . mysql_escape_string($values["cableType"]   ) . "'": "")
                                                                                            . " WHERE id           = '" . mysql_escape_string($values["id"]          ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM  cables WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
	public static function queryConnected($deviceId,$cableType = FALSE,$deviceType = FALSE, $constraint = "1=1")
	{
		$sqlCableType  = "";
		$sqlDeviceType = "";
		if (!empty($cableType )) $sqlCableType  = "AND C.cableType = '" . mysql_escape_string($cableType)  . "'";
                if (!empty($deviceType)) $sqlDeviceType = "AND B.type      = '" . mysql_escape_string($deviceType) . "'";
                $sql = "SELECT C.cableType    AS cableType
                             , C.deviceA_port AS deviceA_port
                             , C.deviceB_port AS deviceB_port
                             , B.id           AS deviceB_id
                             , B.name         AS deviceB_name
                             , B.label        AS deviceB_label
                             , B.customer     AS deviceB_customer
                             , B.rack         AS deviceB_rack
                             , B.position     AS deviceB_position
                             , B.brand        AS deviceB_brand
                             , B.type         AS deviceB_type
                             , B.id           AS id 
                             , B.name         AS name
                             , B.label        AS label
                             , B.customer     AS customer
                             , B.rack         AS rack
                             , B.position     AS position
                             , B.brand        AS brand
                             , B.type         AS type
                             , C.deviceB_port AS port
                           FROM devices A
                             , devices B
                             , cables  C
                         WHERE C.deviceA_id = A.id
                           AND C.deviceB_id = B.id
                           AND A.id = $deviceId
                           AND $constraint 
                           $sqlCableType
                           $sqlDeviceType
                         UNION
                        SELECT C.cableType    AS cableType
                             , C.deviceA_port AS deviceA_port
                             , C.deviceB_port AS deviceB_port
                             , B.id           AS deviceB_id
                             , B.name         AS deviceB_name
                             , B.label        AS deviceB_label
                             , B.customer     AS deviceB_customer
                             , B.rack         AS deviceB_rack
                             , B.position     AS deviceB_position
                             , B.brand        AS deviceB_brand
                             , B.type         AS deviceB_type
                             , B.id           AS id 
                             , B.name         AS name
                             , B.label        AS label
                             , B.customer     AS customer
                             , B.rack         AS rack
                             , B.position     AS position
                             , B.brand        AS brand
                             , B.type         AS type
                             , C.deviceB_port AS port
                           FROM devices A
                             , devices B
                             , cables  C
                         WHERE C.deviceA_id = B.id
                           AND C.deviceB_id = A.id
                           AND A.id = $deviceId
                           AND $constraint 
                           $sqlCableType
                           $sqlDeviceType
                      ORDER BY cableType
                             , deviceB_label       ";
                return $sql;
        }

        public static function getConnected($deviceId,$cableType = FALSE,$deviceType = FALSE, $constraint = "1=1")
        {
                global $database;
                $sql = self::queryConnected($deviceId,$cableType,$deviceType, $constraint);
                $deviceB = $database->fetchObject($sql);
                return $deviceB;
        }

	public static function constrainConnected($cableType = FALSE,$deviceType = FALSE)
	{
		$sqlCableType  = "";
		$sqlDeviceType = "";
		if (!empty($cableType )) $sqlCableType  = "AND C.cableType = '" . mysql_escape_string($cableType)  . "'";
                if (!empty($deviceType)) $sqlDeviceType = "AND B.type      = '" . mysql_escape_string($deviceType) . "'";
		$sql = "SELECT deviceA_id           
                          FROM devices B
                             , cables  C
                         WHERE C.deviceB_id = B.id
                           $sqlCableType
                           $sqlDeviceType
			 UNION
                        SELECT deviceB_id           
                          FROM devices B
                             , cables  C
                         WHERE C.deviceA_id = B.id
                           $sqlCableType
                           $sqlDeviceType";
		return $sql;
	}
}

?>
