<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlDomains extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","d.customer",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
                                     , "(  d.id                = '$1'  "
                                     . "OR domainname      like '%$1%' "
                                     . "OR adminc.sidn_contact = '$1'  "
                                     . "OR techc.sidn_contact  = '$1') "
                                     , $constraint);
		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT d.id         AS id         "
                             . "     , d.domainname AS domainname "
                             . "     , d.customer   AS customer   "
                             . "     , d.owner      AS owner      "
                             . "     , d.adminc     AS adminc     "
                             . "     , d.techc      AS techc      "
                             . "     , d.nameservers AS nameservers"
                             . "  FROM domains  d      "
                             . "     , contacts adminc "
                             . "     , contacts techc  "
                             . " WHERE $constraint  "
                             . "   AND d.adminc = adminc.id "
                             . "   AND d.techc  = techc.id  "
                             . " ORDER BY domainname";
                    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id, domainname FROM domains WHERE $constraint ORDER BY domainname";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                $sql = "INSERT INTO domains "
                     . "          ( customer      "
                     . "          , domainname    "
                     . "          , owner         "
                     . "          , adminc        "
                     . "          , techc         "
                     . "          , nameservers   "
                     . "          )               "
                     . "     VALUES               "
                     . "          ('" . mysql_escape_string($values["customer"] ) . "'"
                     . "          ,'" . mysql_escape_string($values["domainname"] ) . "'"
                     . "          ,'" . mysql_escape_string($values["owner"]      ) . "'"
                     . "          ,'" . mysql_escape_string($values["adminc"]     ) . "'"
                     . "          ,'" . mysql_escape_string($values["techc"]      ) . "'"
                     . "          ,'" . mysql_escape_string($values["nameservers"]) . "'"
                     . "          ) ";
                print($sql);
                $values["id"] = $database->mutate($sql);
		$status       = $values["id"];
                return $status;
        }


        public static function update($values)
        {
                global $database;
                $sql = "UPDATE domains "
                     . ((isset($values["customer"])    && !empty($values["customer"]   )) ? "     , customer    = '" . mysql_escape_string($values["customer"]     ) . "'": "")
                     . ((isset($values["domainname"])  && !empty($values["domainname"] )) ? "     , domainname  = '" . mysql_escape_string($values["domainname"]   ) . "'": "")
                     . ((isset($values["owner"])       && !empty($values["owner"]      )) ? "     , owner       = '" . mysql_escape_string($values["owner"]        ) . "'": "")
                     . ((isset($values["adminc"])      && !empty($values["adminc"]     )) ? "     , adminc      = '" . mysql_escape_string($values["adminc"]       ) . "'": "")
                     . ((isset($values["techc"])       && !empty($values["techc"]      )) ? "     , techc       = '" . mysql_escape_string($values["techc"]        ) . "'": "")
                     . ((isset($values["nameservers"]) && !empty($values["nameservers"])) ? "     , nameservers = '" . mysql_escape_string($values["nameservers"]  ) . "'": "")
                                                                                             . " WHERE id         = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                $sql = "DELETE FROM domains WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
