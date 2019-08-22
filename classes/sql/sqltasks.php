<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlTasks extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		if (strstr($constraint,"parent") === false) $constraint .= " AND parent is NULL ";

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id         =  '$1' "
                                     . "OR subject like '%$1%') "
				     , $constraint);
		    case Settings::ASFORM:
                        return "SELECT id          AS id       "
                             . "     , subject     AS subject  "
                             . "     , category    AS category "
                             . "     , date_end    AS date_end "
                             . "     , owner       AS owner    "
                             . "     , customer    AS customer "
                             . "     , manager     AS manager  "
                             . "     , executor    AS executor "
                             . "     , device      AS device   "
                             . "     , description AS description "
                             . "     , parent      AS parent   "
                             . "     , priority   AS priority "
                             . "     , status     AS status   "
                             . "  FROM tasks        "
                             . " WHERE $constraint  "
                             . " ORDER BY priority  "
                             . "     , date_end     "
                             . "     , category     ";
		    case Settings::ASGRID:
                        return "SELECT id         AS id       "
                             . "     , subject    AS subject  "
                             . "     , category   AS category "
                             . "     , date_end   AS date_end "
                             . "     , owner      AS owner    "
                             . "     , customer   AS customer "
                             . "     , manager    AS manager  "
                             . "     , executor   AS executor "
                             . "     , device     AS device   "
                             . "     , parent     AS parent   "
                             . "     , priority   AS priority "
                             . "     , status     AS status   "
                             . "  FROM tasks        "
                             . " WHERE $constraint  "
                             . " ORDER BY priority  "
                             . "     , date_end     "
                             . "     , category     ";
                    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id, subject FROM tasks ORDER BY subject";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                global $auth;

                $sql = "INSERT INTO tasks         "
                     . "          ( subject       "
                     . "          , category      "
                     . "          , owner         "
                     . "          , date_end      "
                     . "          , executor      "
                     . "          , customer      "
                     . "          , manager       "
                     . "          , device        "
                     . "          , description   "
                     . "          , parent        "
                     . "          , priority      "
                     . "          , status        "
                     . "          )               "
                     . "     VALUES               "
                     . "          ('" . mysql_escape_string($values["subject"]    ) . "'"
                     . "          ,'" . mysql_escape_string($values["category"]   ) . "'"
                     . "          ,'" . $auth->customer->id                         . "'"
                     . "          ,'" . mysql_escape_string($values["date_end"]   ) . "'"
                     . "          ,'" . mysql_escape_string($values["executor"]   ) . "'"
                     . "          ,'" . mysql_escape_string($values["customer"]   ) . "'"
                     . "          ,'" . mysql_escape_string($values["manager"]    ) . "'"
                     . "          ,'" . mysql_escape_string($values["device"]     ) . "'"
                     . "          ,'" . mysql_escape_string($values["description"]) . "'"
                     . "          , " . (empty($values["parent"]) ? "NULL" : "'" . mysql_escape_string($values["parent"]) . "'")
                     . "          ,'" . mysql_escape_string($values["priority"])    . "'"
                     . "          ,'" . mysql_escape_string($values["status"])      . "'"
                     . "          ) ";
                print($sql);
                $values["id"] = $database->mutate($sql);
		$status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE tasks "
                     . ((isset($values["subject"])     && !empty($values["subject"]    )) ? "     , subject     = '" . mysql_escape_string($values["subject"]      ) . "'": "")
                     . ((isset($values["category"])    && !empty($values["category"]   )) ? "     , category    = '" . mysql_escape_string($values["category"]     ) . "'": "")
                     . ((isset($values["manager"])     && !empty($values["manager"]    )) ? "     , manager     = '" . mysql_escape_string($values["manager"]      ) . "'": "")
                     . ((isset($values["customer"])    && !empty($values["customer"]   )) ? "     , customer    = '" . mysql_escape_string($values["customer"]     ) . "'": "")
                     . ((isset($values["date_end"])    && !empty($values["date_end"]   )) ? "     , date_end    = '" . mysql_escape_string($values["date_end"]     ) . "'": "")
                     . ((isset($values["executor"])    && !empty($values["executor"]   )) ? "     , executor    = '" . mysql_escape_string($values["executor"]     ) . "'": "")
                     . ((isset($values["device"])      && !empty($values["device"]     )) ? "     , device      = '" . mysql_escape_string($values["device"]       ) . "'": "")
                     . ((isset($values["description"]) && !empty($values["description"])) ? "     , description = '" . mysql_escape_string($values["description"]  ) . "'": "")
                     . ((isset($values["parent"])      && !empty($values["parent"]))      ? "     , parent      = '" . mysql_escape_string($values["parent"]       ) . "'": "")
                     . ((isset($values["priority"])    && !empty($values["priority"]))    ? "     , priority    = '" . mysql_escape_string($values["priority"]     ) . "'": "")
                     . ((isset($values["status"])      && !empty($values["status"]))      ? "     , status      = '" . mysql_escape_string($values["status"]       ) . "'": "")
                                                                                             . " WHERE id         = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                print($sql);
                error_log($sql);
                $status = $database->mutate($sql);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM tasks WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
