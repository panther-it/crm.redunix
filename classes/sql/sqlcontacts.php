<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../databaseUnit4.php";
require_once __DIR__ . "/sqlcommon.php";
require_once __DIR__ . "/../settings.php";

class SqlContacts extends SqlCommon
{
        public static function query($viewType,$constraint = '1=1')
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("customer","owner",$constraint);
		$constraint = str_replace("owner","c.owner",$constraint);
		$constraint = str_replace("c.c.owner","c.owner",$constraint);
		$constraint = str_replace("sidn_c.owner","sidn_owner",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			            , "(  c.id         = '$1'  "
                                    . "OR c.firstname like '%$1%' "
                                    . "OR c.lastname  like '%$1%' "
                                    . "OR c.email     like '%$1%' "
                                    . "OR c.username  like '%$1%' "
                                    . "OR c.sidn_owner   = '$1'  "
                            	    . "OR c.sidn_contact = '$1') "
				    , $constraint);
		    case Settings::ASFORM:
                        return "SELECT c.id    AS id  "
                              ."     , c.owner AS customer   "
                              ."     , organization          "
                              ."     , o.name as organization_name "
                              ."     , firstname             "
                              ."     , lastname              "
			      ."     , LEFT(firstname,1)              AS initials  "
                              ."     , c.email               "
                              ."     , c.phone AS phone      "
                              ."     , c.phone_mobile AS phone_mobile "
                              ."     , c.fax                 "
                              ."     , gender                "
                              ."     , language              "
                              ."     , grafix_id             "
                              ."     , function              "
			      ."     , street                " 
			      ."     , SUBSTRING_INDEX(street,' ',-1) AS housenr   "
			      ."     , zipcode               " 
			      ."     , city                  " 
                              ."     , username              "
                              ."     , password              "
                              ."     , sidn_owner            "
                              ."     , sidn_contact          "
                              ."  FROM contacts       c      "
                              ."     , organizations  o      "
                              ." WHERE $constraint           "
                              ."   AND c.organization = o.id ";
		    case Settings::ASGRID:
                        return "SELECT c.id    AS id "
                              ."     , c.owner AS customer "
                              ."     , organization      "
                              ."     , o.name as organization_name "
                              ."     , firstname         "
                              ."     , lastname          "
                              ."     , c.email           "
                              ."     , c.phone           "
                              ."     , function          "
                              ."     , sidn_owner        "
                              ."  FROM contacts      c   "
                              ."     , organizations o   "
                              ." WHERE $constraint      "
                              ."   AND c.organization = o.id "
                              ." ORDER BY CONCAT(o.name,firstname)";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT c.id AS id     "
                              ."     , CONCAT(firstname,' ', lastname, ' (', o.name, ')') as name "
                              ."  FROM contacts      c       "
                              ."     , organizations o       "
                              ." WHERE $constraint          "
                              ."   AND c.organization = o.id "
                              ." ORDER BY o.name             "
                              ."        , firstname          ";
		}
        }



        public static function insert(&$values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;

		parent::insert($values);

                $sql = "INSERT INTO contacts "
                     .                                                                        ", owner        " 
                     . ((isset($values["organization"]) && !empty($values["organization"])) ? ", organization " : "")
                     . ((isset($values["firstname"]   ) && !empty($values["firstname"]   )) ? ", firstname    " : "")
                     . ((isset($values["lastname"]    ) && !empty($values["lastname"]    )) ? ", lastname     " : "")
                     . ((isset($values["email"]       ) && !empty($values["email"]       )) ? ", email        " : "")
                     . ((isset($values["phone"]       ) && !empty($values["phone"]       )) ? ", phone        " : "")
                     . ((isset($values["phone_mobile"]) && !empty($values["phone_mobile"])) ? ", phone_mobile " : "")
                     . ((isset($values["fax"]         ) && !empty($values["fax"]         )) ? ", fax          " : "")
                     . ((isset($values["gender"]      ) && !empty($values["gender"]      )) ? ", gender       " : "")
                     . ((isset($values["language"]    ) && !empty($values["language"]    )) ? ", language     " : "")
                     . ((isset($values["grafix_id"]   ) && !empty($values["grafix_id"]   )) ? ", grafix_id    " : "")
                     . ((isset($values["function"]    ) && !empty($values["function"]    )) ? ", function     " : "")
                     . ((isset($values["username"]    ) && !empty($values["username"]    )) ? ", username     " : "")
                     . ((isset($values["password"]    ) && !empty($values["password"]    )) ? ", password     " : "")
                     . ((isset($values["sidn_owner"]  ) && !empty($values["sidn_owner"]  )) ? ", sidn_owner   " : "")
                     . ((isset($values["sidn_contact"]) && !empty($values["sidn_contact"])) ? ", sidn_contact " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["customer"]    ) && !empty($values["customer"]    )) ? ",'" . mysql_escape_string($values["customer"]    ) . "'": ",'" . $auth->customer->id . "'")
                     . ((isset($values["organization"]) && !empty($values["organization"])) ? ",'" . mysql_escape_string($values["organization"]) . "'": "")
                     . ((isset($values["firstname"]   ) && !empty($values["firstname"]   )) ? ",'" . mysql_escape_string($values["firstname"]   ) . "'": "")
                     . ((isset($values["lastname"]    ) && !empty($values["lastname"]    )) ? ",'" . mysql_escape_string($values["lastname"]    ) . "'": "")
                     . ((isset($values["email"]       ) && !empty($values["email"]       )) ? ",'" . mysql_escape_string($values["email"]       ) . "'": "")
                     . ((isset($values["phone"]       ) && !empty($values["phone"]       )) ? ",'" . mysql_escape_string($values["phone"]       ) . "'": "")
                     . ((isset($values["phone_mobile"]) && !empty($values["phone_mobile"])) ? ",'" . mysql_escape_string($values["phone_mobile"]) . "'": "")
                     . ((isset($values["fax"]         ) && !empty($values["fax"]         )) ? ",'" . mysql_escape_string($values["fax"]         ) . "'": "")
                     . ((isset($values["gender"]      ) && !empty($values["gender"]      )) ? ",'" . mysql_escape_string($values["gender"]      ) . "'": "")
                     . ((isset($values["language"]    ) && !empty($values["language"]    )) ? ",'" . mysql_escape_string($values["language"]    ) . "'": "")
                     . ((isset($values["grafix_id"]   ) && !empty($values["grafix_id"]   )) ? ",'" . mysql_escape_string($values["grafix_id"]   ) . "'": "")
                     . ((isset($values["function"]    ) && !empty($values["function"]    )) ? ",'" . mysql_escape_string($values["function"]    ) . "'": "")
                     . ((isset($values["username"]    ) && !empty($values["username"]    )) ? ",'" . mysql_escape_string($values["username"]    ) . "'": "")
                     . ((isset($values["password"]    ) && !empty($values["password"]    )) ? ",'" . mysql_escape_string($values["password"]    ) . "'": "")
                     . ((isset($values["sidn_owner"]  ) && !empty($values["sidn_owner"]  )) ? ",'" . mysql_escape_string($values["sidn_owner"]  ) . "'": "")
                     . ((isset($values["sidn_contact"]) && !empty($values["sidn_contact"])) ? ",'" . mysql_escape_string($values["sidn_contact"]) . "'": "")
                     . "          ) ";
                $sql = preg_replace("/, /", "( ", $sql, 1);
                $sql = preg_replace("/,'/", "('", $sql, 1);
                #print($sql);
                $values["id"] = $database->mutate($sql);
                $status       = $databaseUnit4->mutate($values);
                return $status;
        }


        public static function update($values)
        {
                global $database;
                global $databaseUnit4;

		if (is_object($values))
			$values = get_object_vars($values);

		parent::update($values);

                $sql = "UPDATE contacts "
                     . ((isset($values["organization"]) && !empty($values["organization"])) ? ", organization = '" . mysql_escape_string($values["organization"]) . "'" : "")
                     . ((isset($values["firstname"]   ) && !empty($values["firstname"]   )) ? ", firstname    = '" . mysql_escape_string($values["firstname"]   ) . "'" : "")
                     . ((isset($values["lastname"]    ) && !empty($values["lastname"]    )) ? ", lastname     = '" . mysql_escape_string($values["lastname"]    ) . "'" : "")
                     . ((isset($values["email"]       ) && !empty($values["email"]       )) ? ", email        = '" . mysql_escape_string($values["email"]       ) . "'" : "")
                     . ((isset($values["phone"]       ) && !empty($values["phone"]       )) ? ", phone        = '" . mysql_escape_string($values["phone"]       ) . "'" : "")
                     . ((isset($values["phone_mobile"]) && !empty($values["phone_mobile"])) ? ", phone_mobile = '" . mysql_escape_string($values["phone_mobile"]) . "'" : "")
                     . ((isset($values["fax"]         ) && !empty($values["fax"]         )) ? ", fax          = '" . mysql_escape_string($values["fax"]         ) . "'" : "")
                     . ((isset($values["gender"]      ) && !empty($values["gender"]      )) ? ", gender       = '" . mysql_escape_string($values["gender"]      ) . "'" : "")
                     . ((isset($values["language"]    ) && !empty($values["language"]    )) ? ", language     = '" . mysql_escape_string($values["language"]    ) . "'" : "")
                     . ((isset($values["grafix_id"]   )                                   ) ? ", grafix_id    = '" . mysql_escape_string($values["grafix_id"]   ) . "'" : "")
                     . ((isset($values["function"]    ) && !empty($values["function"]    )) ? ", function     = '" . mysql_escape_string($values["function"]    ) . "'" : "")
                     . ((isset($values["username"]    ) && !empty($values["username"]    )) ? ", username     = '" . mysql_escape_string($values["username"]    ) . "'" : "")
                     . ((isset($values["password"]    ) && !empty($values["password"]    )) ? ", password     = '" . mysql_escape_string($values["password"]    ) . "'" : "")
                     . ((isset($values["sidn_owner"]  ) && !empty($values["sidn_owner"]  )) ? ", sidn_owner   = '" . mysql_escape_string($values["sidn_owner"]  ) . "'" : "")
                     . ((isset($values["sidn_contact"]) && !empty($values["sidn_contact"])) ? ", sidn_contact = '" . mysql_escape_string($values["sidn_contact"]) . "'" : "")
                                                                                     . "  WHERE id  = '" . mysql_escape_string($values["id"]           ) . "'";
                $sql = preg_replace("/,/", "SET", $sql, 1);
                #print($sql);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $status;
        }


        public static function delete($values)
        {
                global $database;
                global $databaseUnit4;
                $sql = "DELETE FROM  contacts WHERE id = '" . mysql_escape_string($values["id"]) . "'";
                #print($sql);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $status;
        }
}

?>
