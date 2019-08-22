<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../databaseUnit4.php";
require_once __DIR__ . "/sqlcommon.php";
require_once __DIR__ . "/sqlcountries.php";
require_once __DIR__ . "/../settings.php";

class SqlOrganizations extends SqlCommon
{
        public static function query($viewType,$constraint = '1=1')
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("customer","owner",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			            , "(  id         = '$1'  "
                        	    . "OR name    like '%$1%' "
                            	    . "OR street  like '%$1%' "
        	                    . "OR city    like '%$1%' "
                	            . "OR vatid   like '%$1%' "
                 	            . "OR phone   like '%$1%')"
				    , $constraint);
		    case Settings::ASFORM:
                        return "SELECT id               "
                              ."     , owner AS customer"
                              ."     , name             "
                              ."     , email            "
                              ."     , phone            "
                              ."     , fax              "
                              ."     , street           "
                              ."     , zipcode          "
                              ."     , city             "
                              ."     , country          "
                              ."     , vatid            "
                              ."  FROM organizations    "
                              ." WHERE $constraint     ";
			    case Settings::ASGRID:
                        return "SELECT id                "
                              ."     , owner AS customer "
                              ."     , name              "
                              ."     , email             "
                              ."     , phone             "
                              ."     , street            "
                              ."     , zipcode           "
                              ."     , city              "
                              ."  FROM organizations     "
                              ." WHERE $constraint      "
                              ." ORDER BY name           "; 
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id               "
                              ."     , name             "
                              ."  FROM organizations    "
                              ." WHERE $constraint     "
                              ." ORDER BY name          ";
	}
        }


        public static function insert(&$values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;
		$sqlCountries = new SqlCountries();

		parent::insert($values);

                $sql = "INSERT INTO organizations "
                     .                                                                ", owner    " 
                     . ((isset($values["name"]    ) && !empty($values["name"]    )) ? ", name     " : "")
                     . ((isset($values["street"]  ) && !empty($values["street"]  )) ? ", street   " : "")
                     . ((isset($values["zipcode"] ) && !empty($values["zipcode"] )) ? ", zipcode  " : "")
                     . ((isset($values["city"]    ) && !empty($values["city"]    )) ? ", city     " : "")
                     . ((isset($values["email"]   ) && !empty($values["email"]   )) ? ", email    " : "")
                     . ((isset($values["phone"]   ) && !empty($values["phone"]   )) ? ", phone    " : "")
                     . ((isset($values["fax"]     ) && !empty($values["fax"]     )) ? ", fax      " : "")
                     . ((isset($values["country"] ) && !empty($values["country"] )) ? ", country  " : "")
                     . ((isset($values["vatid"]   ) && !empty($values["vatid"]   )) ? ", vatid    " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["customer"]) && !empty($values["customer"])) ? ",'" . mysql_escape_string($values["customer"]) . "'" : ",'" . $auth->customer->id . "'")
                     . ((isset($values["name"]    ) && !empty($values["name"]    )) ? ",'" . mysql_escape_string($values["name"]    ) . "'" : "")
                     . ((isset($values["street"]  ) && !empty($values["street"]  )) ? ",'" . mysql_escape_string($values["street"]  ) . "'" : "")
                     . ((isset($values["zipcode"] ) && !empty($values["zipcode"] )) ? ",'" . mysql_escape_string($values["zipcode"] ) . "'" : "")
                     . ((isset($values["city"]    ) && !empty($values["city"]    )) ? ",'" . mysql_escape_string($values["city"]    ) . "'" : "")
                     . ((isset($values["email"]   ) && !empty($values["email"]   )) ? ",'" . mysql_escape_string($values["email"]   ) . "'" : "")
                     . ((isset($values["phone"]   ) && !empty($values["phone"]   )) ? ",'" . mysql_escape_string($values["phone"]   ) . "'" : "")
                     . ((isset($values["fax"]     ) && !empty($values["fax"]     )) ? ",'" . mysql_escape_string($values["fax"]     ) . "'" : "")
                     . ((isset($values["country"] ) && !empty($values["country"] )) ? ",'" . mysql_escape_string($values["country"] ) . "'" : "")
                     . ((isset($values["vatid"]   ) && !empty($values["vatid"]   )) ? ",'" . mysql_escape_string($values["vatid"]   ) . "'" : "")
                     . "          ) ";
                $sql = preg_replace("/, /", "( ", $sql, 1);
                $sql = preg_replace("/,'/", "('", $sql, 1);
                #print($sql);
                $values["id"] = $database->mutate($sql);
		$status      .= $sqlCountries->insert($values["country"]);
		$status       = $databaseUnit4->mutate($values);
                return $status;
        }


        public static function update($values)
        {
                global $database;
                global $databaseUnit4;
		$sqlCountries = new SqlCountries();

		parent::update($values);

                $sql = "UPDATE organizations "
                     . ((isset($values["name"]    ) && !empty($values["name"]    )) ? ", name    = '" . mysql_escape_string($values["name"]    ) . "'" : "")
                     . ((isset($values["street"]  ) && !empty($values["street"]  )) ? ", street  = '" . mysql_escape_string($values["street"]  ) . "'" : "")
                     . ((isset($values["zipcode"] ) && !empty($values["zipcode"] )) ? ", zipcode = '" . mysql_escape_string($values["zipcode"] ) . "'" : "")
                     . ((isset($values["city"]    ) && !empty($values["city"]    )) ? ", city    = '" . mysql_escape_string($values["city"]    ) . "'" : "")
                     . ((isset($values["email"]   ) && !empty($values["email"]   )) ? ", email   = '" . mysql_escape_string($values["email"]   ) . "'" : "")
                     . ((isset($values["phone"]   ) && !empty($values["phone"]   )) ? ", phone   = '" . mysql_escape_string($values["phone"]   ) . "'" : "")
                     . ((isset($values["fax"]     ) && !empty($values["fax"]     )) ? ", fax     = '" . mysql_escape_string($values["fax"]     ) . "'" : "")
                     . ((isset($values["country"] ) && !empty($values["country"] )) ? ", country = '" . mysql_escape_string($values["country"] ) . "'" : "")
                     . ((isset($values["vatit"]   ) && !empty($values["vatit"]   )) ? ", vatit   = '" . mysql_escape_string($values["vatit"]   ) . "'" : "")
                     . ((isset($values["customer"]) && !empty($values["customer"])) ? ", owner   = '" . mysql_escape_string($values["customer"]) . "'" : "")
                     . ((isset($values["owner"   ]) && !empty($values["owner"   ])) ? ", owner   = '" . mysql_escape_string($values["owner"   ]) . "'" : "")
                                                                                  . "  WHERE id= '" . mysql_escape_string($values["id"])      . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                #print($sql);
		$status  = $sqlCountries->insert($values["country"]);
                $status .= $database->mutate($sql)
		        .  $databaseUnit4->mutate($values);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                global $databaseUnit4;
                $sql = "DELETE FROM  organizations WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                #print($sql);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $status;
        }
}

?>
