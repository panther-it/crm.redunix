<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlAuthorization extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		//parent::query($viewType, $constraint);
		$constraint = str_replace("owner","customer",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id         =  '$1' "
                                     . "OR section like '%$1%') "
				     , $constraint);
		    case Settings::ASGRID:
		    case Settings::ASFORM:
                        return "SELECT id
                                     , customer
                                     , contact 
                                     , section
                                     , level 
                                  FROM authorization
                                 WHERE $constraint
                              ORDER BY customer
                                     , section       ";
		    case Settings::ASLIST:
                        return "SELECT MAX(id)       AS id
                                     , MAX(customer) AS customer
                                     , section
                                     , MAX(level)    AS level 
                                  FROM authorization
                                 WHERE $constraint
                              GROUP BY section
                              ORDER BY customer
                                     , section       ";
	            case Settings::ASCREATELIST:
                        return "SELECT id
                                     , CONCAT(c.name     , ': '
                                             ,a.section  , ': '
                                             ,a.level
                                             )               AS access
                                  FROM authorization a
                                     , customers     c
                                 WHERE a.customer    = c.id
                              ORDER BY access";
		}
        }


	public static function access($customerId)
	{
		return false; 
	}

        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO authorization "
                     . "          ( customer      "
                     . "          , section       "
                     . "          , level         "
                     . "          , contact       "
                     . "          )               "
                     . "     VALUES               "
                     . "          ('" . mysql_escape_string($values["customer"] ) . "'"
                     . "          ,'" . mysql_escape_string($values["section"]  ) . "'"
                     . "          ,'" . mysql_escape_string($values["level"]    ) . "'"
                     . "          ,'" . mysql_escape_string($values["contact"]  ) . "'"
                     . "          ) ";
                print($sql);
                $values["id"] = $database->mutate($sql);
                $status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE authorization "
                     . ((isset($values["customer"]) && !empty($values["customer"])) ? " , customer = '" . mysql_escape_string($values["customer"]) . "'": "")
                     . ((isset($values["section"] ) && !empty($values["section"] )) ? " , section  = '" . mysql_escape_string($values["section"] ) . "'": "")
                     . ((isset($values["level"]   ) && !empty($values["level"]   )) ? " , level    = '" . mysql_escape_string($values["level"]   ) . "'": "")
                     . ((isset($values["contact"] ) && !empty($values["contact"] )) ? " , contact  = '" . mysql_escape_string($values["contact"] ) . "'": "")
                                                                                    . " WHERE id   = '" . mysql_escape_string($values["id"]      ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
		if (!empty($values["id"]))
                    $sql = "DELETE FROM  authorization WHERE id = '" . mysql_escape_string($values["id"]) . "'";
		else
                    if (!empty($values["customer"]) && !empty($values["section"]) && !empty($values["level"]) && !empty($values["contact"]))
                        $sql = "DELETE FROM  authorization WHERE customer = '" . mysql_escape_string($values["customer"]) . "'"
                             .                           "   AND section  = '" . mysql_escape_string($values["section"] ) . "'"
                             .                           "   AND contact  = '" . mysql_escape_string($values["contact"] ) . "'"
                             .                           "   AND level    = '" . mysql_escape_string($values["level"]   ) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
