<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlNameservers extends SqlCommon
{
        public static function query($viewType,$constraint = '1=1')
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","customer",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  id     =  '$1' "
                                     . "OR ns1 like '%$1%' "
                                     . "OR ns2 like '%$1%' "
                                     . "OR ns3 like '%$1%') "
				     , $constraint);
		    case Settings::ASGRID:
		    case Settings::ASFORM:
                        return "SELECT id
                                     , ns1
                                     , ns2
                                     , ns3
                                     , customer 
                                  FROM nameservers 
                                 WHERE $constraint 
                              ORDER BY ns1, ns2, ns3";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id, CONCAT(ns1,',',ns2) as ns 
                                  FROM nameservers 
                                 WHERE $constraint 
                              ORDER BY ns1, ns2";
		}
        }


        public static function getIdByNS($ns)
        {
                global $database;
                $sql = "SELECT id "
                     . "  FROM nameservers "
                     . " WHERE ns1 like '%" . $ns . "%'"
                     . "    OR ns2 like '%" . $ns . "%'";
                $rs = $database->query($sql);
                if (mysql_num_rows($rs) == 0) 
                {
                        return -1;
                }
                else
                {
                        $r = mysql_fetch_object($rs);
                        return $r->id;
                }
        }


        public static function insert(&$values)
        {
                global $database;
                global $auth;

                $sql = "INSERT INTO nameservers "
                     .                                                      ", customer  "
                     . ((isset($values["ns1"]) && !empty($values["ns1"])) ? ", ns1  " : "")
                     . ((isset($values["ns2"]) && !empty($values["ns2"])) ? ", ns2  " : "")
                     . ((isset($values["ns3"]) && !empty($values["ns3"])) ? ", ns3  " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["customer"]) && !empty($values["customer"])) ? ",'" . mysql_escape_string($values["customer"]) . "'" : "'" . $auth->customer->id . "'")
                     . ((isset($values["ns1"]) && !empty($values["ns1"])) ? ",'" . mysql_escape_string($values["ns1"]) . "'" : "")
                     . ((isset($values["ns2"]) && !empty($values["ns2"])) ? ",'" . mysql_escape_string($values["ns2"]) . "'" : "")
                     . ((isset($values["ns3"]) && !empty($values["ns3"])) ? ",'" . mysql_escape_string($values["ns3"]) . "'" : "")
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
                global $auth;

                $sql = "UPDATE nameservers "
                     . ((isset($values["customer"]) && !empty($values["customer"])) ? ", customer = '" . mysql_escape_string($values["customer"]) . "'" : "")
                     . ((isset($values["ns1"]) && !empty($values["ns1"])) ? ", ns1 = '" . mysql_escape_string($values["ns1"]) . "'" : "")
                     . ((isset($values["ns2"]) && !empty($values["ns2"])) ? ", ns2 = '" . mysql_escape_string($values["ns2"]) . "'" : "")
                     . ((isset($values["ns3"]) && !empty($values["ns3"])) ? ", ns3 = '" . mysql_escape_string($values["ns3"]) . "'" : "")
                                                                                        . "  WHERE id       = '" . mysql_escape_string($values["id"]        ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                global $auth;

                $sql = "DELETE FROM nameservers " 
                     . "      WHERE id       = '" . mysql_escape_string($values["id"])       . "'"
                     . "        AND customer = '" . ((isset($values["customer"]) && !empty($values["customer"])) ? mysql_escape_string($values["customer"]) : $auth->customer->id) . "'";
                print($sql);
                $status = $database->mutate($sql);
                return $status;
        }
}

?>
