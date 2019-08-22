<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../databaseUnit4.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlProducts extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","p.owner",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  p.id       =  '$1' "
                                     . "OR p.label like '%$1%') "
				     , $constraint);
 		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT p.owner
                                     , p.id
                                     , p.label
                                     , p.info_uri
                                     , p.recap_uri 
                                     , p.enabled
                                     , p.type
                                     , p.recurring
                                     , p.price_1
                                     , p.price_3
                                     , p.price_6
                                     , p.price_12
                                     , pp.parent  as parent
                                     , pp.display as display
                                     , pop.label  as parent_label
                                  FROM products p
                                     , products_products pp
                             RIGHT JOIN products pop
                                    ON pp.parent = pop.id
                                 WHERE pp.child = p.id
                                   AND $constraint
                              GROUP BY pp.child
                              ORDER BY pp.display
                                     , p.label";
		    case Settings::ASLIST:
		    case Settings::ASCREATELIST:
                        return "SELECT p.id
                                     , MAX(p.label) AS label
                                  FROM products p
                                     , products_products pp
                                 WHERE pp.child = p.id
                                   AND enabled = 1
                                   AND $constraint
                              GROUP BY p.id
                              ORDER BY pp.display
                                     , p.label";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;

                $sql = "INSERT INTO products "
                     . "          , owner    "
                     . ((isset($values["label"])     && !empty($values["label"]    )) ? ", label    " : "")
                     . ((isset($values["info_uri"])                                 ) ? ", info_uri " : "")
                     . ((isset($values["recap_uri"])                                ) ? ", recap_uri" : "")
                     . ((isset($values["enabled"])   && !empty($values["enabled"]  )) ? ", enabled  " : "")
                     . ((isset($values["type"])      && !empty($values["type"]     )) ? ", type     " : "")
                     . ((isset($values["recurring"]) && !empty($values["recurring"])) ? ", recurring" : "")
                     . ((isset($values["price_1"])   && strlen($values["price_1"]  > 0)) ? ", price_1  " : "")
                     . ((isset($values["price_3"])   && strlen($values["price_3"]  > 0)) ? ", price_3  " : "")
                     . ((isset($values["price_6"])   && strlen($values["price_6"]  > 0)) ? ", price_6  " : "")
                     . ((isset($values["price_12"])  && strlen($values["price_12"] > 0)) ? ", price_12 " : "")
                     . "          )               "
                     . "     VALUES               "
                     . "                                                                 ,'" . mysql_escape_string($auth->customer->id ) . "'" 
                     . ((isset($values["label"]    ) && !empty($values["label"]    )) ? ",'" . mysql_escape_string($values["label"]    ) . "'" : "")
                     . ((isset($values["info_uri"] )                                ) ? ",'" . mysql_escape_string($values["info_uri"] ) . "'" : "")
                     . ((isset($values["recap_uri"])                                ) ? ",'" . mysql_escape_string($values["recap_uri"]) . "'" : "")
                     . ((isset($values["enabled"]  ) && !empty($values["enabled"]  )) ? ", " . mysql_escape_string($values["enabled"]  ) . " " : "")
                     . ((isset($values["type"]     ) && !empty($values["type"]     )) ? ",'" . mysql_escape_string($values["type"]     ) . "'" : "")
                     . ((isset($values["recurring"]) && !empty($values["recurring"])) ? ", " . mysql_escape_string($values["recurring"]) . " " : "")
                     . ((isset($values["price_1"]  ) && strlen($values["price_1"]  > 0)) ? ",'" . mysql_escape_string($values["price_1"]  ) . "'" : "")
                     . ((isset($values["price_3"]  ) && strlen($values["price_3"]  > 0)) ? ",'" . mysql_escape_string($values["price_3"]  ) . "'" : "")
                     . ((isset($values["price_6"]  ) && strlen($values["price_6"]  > 0)) ? ",'" . mysql_escape_string($values["price_6"]  ) . "'" : "")
                     . ((isset($values["price_12"] ) && strlen($values["price_12"] > 0)) ? ",'" . mysql_escape_string($values["price_12"] ) . "'" : "")
                     . "          ) ";
                $sql = preg_replace("/, /","( ",$sql,1);
                $sql = preg_replace("/,'/","('",$sql,1);
                print($sql);
                $values["id"]    = $database->mutate($sql);
		//$values["class"] = "Product"; //unit4 ubc/api url
		//if ($values["type"] != "VALUE")
			$status  = $databaseUnit4->mutate($values);
		self::insertProductsProducts($values);
                return $status;
        }

	public static function insertProductsProducts($values)
	{
                global $database;
                global $databaseUnit4;

                $sql = "INSERT INTO products_products "
                     . ((isset($values["parent"] )  && !empty($values["parent" ])) ? ", parent  " : "")
                     . ((isset($values["id"    ] )  && !empty($values["id"     ])) ? ", child   " : "")
                     . ((isset($values["child"  ])  && !empty($values["child"  ])) ? ", child   " : "")
                     . ((isset($values["display"])  && !empty($values["display"])) ? ", display " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["parent"] ) && !empty($values["parent"] )) ? ",'" . mysql_escape_string($values["parent"] ) . "'" : "")
                     . ((isset($values["id"]     ) && !empty($values["id"]     )) ? ",'" . mysql_escape_string($values["id"]     ) . "'" : "")
                     . ((isset($values["child"]  ) && !empty($values["child"]  )) ? ",'" . mysql_escape_string($values["child"]  ) . "'" : "")
                     . ((isset($values["display"]) && !empty($values["display"])) ? ", " . mysql_escape_string($values["display"]) . " " : "")
                     . "          ) ";
                $sql = preg_replace("/, /","( ",$sql,1);
                $sql = preg_replace("/,'/","('",$sql,1);
                print($sql);
                $status = $database->mutate($sql);
		//      . $databaseUnit4->mutate($values);
                return $status;
 	}

        public static function update($values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;

                $sql = "UPDATE products "
                     . ((isset($values["label"]    ) && !empty($values["label"]    )) ? ", label     = '" . mysql_escape_string($values["label"])     . "'" : "")
                     . ((isset($values["info_uri"] )                                ) ? ", info_uri  = '" . mysql_escape_string($values["info_uri"])  . "'" : "")
                     . ((isset($values["recap_uri"])                                ) ? ", recap_uri = '" . mysql_escape_string($values["recap_uri"]) . "'" : "")
                     . ((isset($values["enabled"]  ) && !empty($values["enabled"]  )) ? ", enabled   =  " . mysql_escape_string($values["enabled"])   . " " : "")
                     . ((isset($values["type"]     ) && !empty($values["type"]     )) ? ", type      = '" . mysql_escape_string($values["type"])      . "'" : "")
                     . ((isset($values["recurring"]) && !empty($values["recurring"])) ? ", recurring =  " . mysql_escape_string($values["recurring"]) . " " : "")
                     . ((isset($values["price_1"]  ) && strlen($values["price_1"])  >0) ? ", price_1   = '" . mysql_escape_string($values["price_1"])   . "'" : "")
                     . ((isset($values["price_3"]  ) && strlen($values["price_3"])  >0) ? ", price_3   = '" . mysql_escape_string($values["price_3"])   . "'" : "")
                     . ((isset($values["price_6"]  ) && strlen($values["price_6"])  >0) ? ", price_6   = '" . mysql_escape_string($values["price_6"])   . "'" : "")
                     . ((isset($values["price_12"] ) && strlen($values["price_12"]) >0) ? ", price_12  = '" . mysql_escape_string($values["price_12"])  . "'" : "")
                                                                                     . "  WHERE id   = '" . mysql_escape_string($values["id"]      ) . "'"
                                                                                     . "  AND owner  = '" . mysql_escape_string($auth->customer->id) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql);
		//$values["class"] = "Product"; //unit4 ubc/api url
		if ($values["type"] != "VALUE")
			$status  .= $databaseUnit4->mutate($values);
		self::updateProductsProducts($values);
                return $sql . "\n" . $status;
        }

	public static function updateProductsProducts($values)
	{
                global $database;
                global $databaseUnit4;

                $sql = "UPDATE products_products "
                     . ((isset($values["display"])  && !empty($values["display"])) ? ", display = '" . mysql_escape_string($values["display"]) . "'" : "")
                     . " WHERE child  = '" . mysql_escape_string($values["child"]) .  mysql_escape_string($values["id"]) . "'"
                     . "   AND parent = '" . mysql_escape_string($values["parent"]) . "'";
                $sql = preg_replace("/, /","SET ",$sql,1);
                print($sql);
                $status = $database->mutate($sql);
		//      . $databaseUnit4->mutate($values);
                return $status;
 	}


        public static function delete($values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;
                $sql = "DELETE FROM  products_products WHERE child  = '" . mysql_escape_string($values["id"]      ) . "'"
                                                   . "   AND parent = '" . mysql_escape_string($values["parent"]  ) . "'";
                print($sql);
                $status = $database->mutate($sql);
                $sql = "DELETE FROM  products WHERE id    = '" . mysql_escape_string($values["id"]      ) . "'"
                                            . " AND owner = '" . mysql_escape_string($auth->customer->id) . "'"
                                            . " AND NOT id IN (SELECT child  FROM products_products)"
                                            . " AND NOT id IN (SELECT parent FROM products_products)";
                print($sql);
                $status = $database->mutate($sql);
		//$values["class"] = "Product"; //unit4 ubc/api url
		if ($values["type"] != "VALUE")
			$status  .= $databaseUnit4->mutate($values);
                return $status;
        }
}

?>
