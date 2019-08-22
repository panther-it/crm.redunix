<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../databaseUnit4.php";
require_once __DIR__ . "/sqlcommon.php";

class SqlOrders extends SqlCommon
{
        public static function query($viewType, $constraint = "1=1")
        {
		parent::query($viewType, $constraint);
		$constraint = str_replace("owner","o.customer",$constraint);

		switch($viewType)
		{
		    case Settings::ASSEARCH:
			$constraint = preg_replace("/^(.+)/"
			             , "(  o.id      =  '$1'   "
                                     . "OR o.label like '%$1%'  "
                                     . "OR p.label like '%$1%') "
				     , $constraint);
 		    case Settings::ASFORM:
		    case Settings::ASGRID:
                        return "SELECT o.id           AS id 
                                     , o.customer
                                     , o.product
                                     , o.enabled
                                     , p.label        AS product_label
                                     , p.type         AS product_type
                                     , o.label        AS label
                                     , ROUND(
                                       IFNULL(o.price,
                                       CASE o.recurring
                                           WHEN 'M' then p.price_1
                                           WHEN 'K' then p.price_3
                                           WHEN 'H' then p.price_6
                                           WHEN 'J' then p.price_12
                                       END),2)        AS price
                                     , ROUND(
                                       CASE o.recurring
                                           WHEN 'M' THEN IFNULL(o.price,p.price_1)
                                           WHEN 'K' THEN IFNULL(o.price,p.price_3)*3
                                           WHEN 'H' THEN IFNULL(o.price,p.price_6)*6
                                           WHEN 'J' THEN IFNULL(o.price,p.price_12)*12
                                       END,2)         AS price_total
                                     , o.date_start
                                     , o.date_end
                                     , o.recurring   
                                     , op.id          AS parent_id
                                     , op.id          AS parent
                                     , op.product     AS parent_product
                                     , pap.label      AS parent_product_label
                                     , op.enabled     AS parent_enabled
                                     , op.label       AS parent_label
                                     , ROUND(
                                       IFNULL(op.price,
                                       CASE op.recurring
                                           WHEN 'M' then pap.price_1
                                           WHEN 'K' then pap.price_3
                                           WHEN 'H' then pap.price_6
                                           WHEN 'J' then pap.price_12
                                       END),2)        AS parent_price
                                     , op.date_start  AS parent_date_start
                                     , op.date_end    AS parent_date_end
                                     , op.recurring   AS parent_recurring
                                     , pp.parent      AS product_group
				     , COUNT(co.child) AS children_count
                                  FROM orders o
                             LEFT JOIN orders_orders co
                                    ON co.parent  = o.id
                                     , products p
                                     , customers c
                                     , products_products pp
                                     , orders_orders oo
                             LEFT JOIN orders op
                                    ON oo.parent  = op.id
                             LEFT JOIN products pap
                                    ON op.product = pap.id
                                 WHERE oo.child   = o.id
                                   AND pp.child   = o.product
                                   AND o.product  = p.id
                                   AND o.customer = c.id
                                   AND $constraint
                              GROUP BY pp.child, co.parent
                              ORDER BY o.customer 
                                     , o.date_start
                                     , p.label
                                     , o.label";
		    case Settings::ASLIST:
                    case Settings::ASCREATELIST:
                        return "SELECT id
                                     , concat(p.label, o.label) AS label 
                                  FROM orders o
                                     , products p
                                 WHERE o.product = p.id
                                   AND o.enabled = 1
                                   AND $constraint
                              ORDER BY label";
		}
        }

        public static function insert(&$values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;

		parent::insert($values);

                $sql = "INSERT INTO orders "
                     . ((isset($values["customer"])    && !empty($values["customer"]  )) ? ", customer   " : "")
                     . ((isset($values["product"])     && !empty($values["product"]   )) ? ", product    " : "")
                     . ((isset($values["enabled"])     && ($values["enabled"] != ""   )) ? ", enabled    " : "")
                     . ((isset($values["label"])       && !empty($values["label"]     )) ? ", label      " : "")
                     . ((isset($values["price"])       && !empty($values["price"]     )) ? ", price      " : "")
                     . ((isset($values["date_start"])  && !empty($values["date_start"])) ? ", date_start " : "")
                     . ((isset($values["date_end"])    && !empty($values["date_end"]  )) ? ", date_end   " : "")
                     . ((isset($values["recurring"])   && !empty($values["recurring"] )) ? ", recurring  " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["customer"]  ) && !empty($values["customer"]  )) ? ",'" . mysql_escape_string($values["customer"]  ) . "'" : "")
                     . ((isset($values["product"]   ) && !empty($values["product"]   )) ? ",'" . mysql_escape_string($values["product"]   ) . "'" : "")
                     . ((isset($values["enabled"]   ) && ($values["enabled"] != ""   )) ? ", " . mysql_escape_string($values["enabled"]   ) . " " : "")
                     . ((isset($values["label"]     ) && !empty($values["label"]     )) ? ",'" . mysql_escape_string($values["label"]     ) . "'" : "")
                     . ((isset($values["price"]     ) && !empty($values["price"]     )) ? ", " . mysql_escape_string($values["price"]     ) . " " : "")
                     . ((isset($values["date_start"]) && !empty($values["date_start"])) ? ",'" . mysql_escape_string($values["date_start"]) . "'" : "")
                     . ((isset($values["date_end"]  ) && !empty($values["date_end"]  )) ? ",'" . mysql_escape_string($values["date_end"]  ) . "'" : "")
                     . ((isset($values["recurring"] ) && !empty($values["recurring"] )) ? ",'" . mysql_escape_string($values["recurring"] ) . "'" : "")
                     . "          ) ";
                $sql = preg_replace("/, /","( ",$sql,1);
                $sql = preg_replace("/,'/","('",$sql,1);
                #print($sql);
                $values["id"] = $database->mutate($sql);
		self::insertOrdersOrders($values);
		$values = $database->fetchArray(self::query(Settings::ASFORM,"o.id=" . $values["id"])); //get more data
		$values["action"] = "insert"; //re-add

		//Create "Samengesteld Product"   or   
                //Add "Component" to "Samengesteld Product"
		$values["class"] = "ProductComponent";
		$status = "Unit4.ProductComponent.insert: " . $databaseUnit4->mutate($values) . "\n";

		//Create or update "AbonnementSoort" + "Abonnement" 
		$values["class"] = "Subscription";
		$status .= "Unit4.Subscription.insert: " . $databaseUnit4->mutate($values) . "\n";

                return $status;
        }

	public static function insertOrdersOrders(&$values)
	{
                global $database;
                //global $databaseUnit4;

		parent::insert($values);
		$values["class"] = "OrdersOrder"; //unit4 .aspx file reference

                $sql = "INSERT INTO orders_orders "
                     . ((isset($values["parent"] )  && !empty($values["parent" ])) ? ", parent  " : "")
                     . ((isset($values["id"    ] )  && !empty($values["id"     ])) ? ", child   " : "")
                     . "          )               "
                     . "     VALUES               "
                     . ((isset($values["parent"] ) && !empty($values["parent"] )) ? ",'" . mysql_escape_string($values["parent"] ) . "'" : "")
                     . ((isset($values["id"]     ) && !empty($values["id"]     )) ? ",'" . mysql_escape_string($values["id"]     ) . "'" : "")
                     . "          ) ";
                $sql = preg_replace("/, /","( ",$sql,1);
                $sql = preg_replace("/,'/","('",$sql,1);
                #print($sql);
                $status = $database->mutate($sql);
		//        . $databaseUnit4->mutate($values);
                return $status;
 	}

        public static function update($values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;

                parent::update($values);
                
                $sql = "UPDATE orders "
                     . ((isset($values["customer"]  ) && !empty($values["customer"]  )) ? ", customer   = '" . mysql_escape_string($values["customer"])   . "'" : "")
                     . ((isset($values["product"]   ) && !empty($values["product"]   )) ? ", product    = '" . mysql_escape_string($values["product"])    . "'" : "")
                     . ((isset($values["enabled"]   ) && !empty($values["enabled"]   )) ? ", enabled    =  " . mysql_escape_string($values["enabled"])    . " " : "")
                     . ((isset($values["label"]     ) && !empty($values["label"]     )) ? ", label      = '" . mysql_escape_string($values["label"])      . "'" : "")
                     . ((isset($values["price"]     ) && is_numeric($values["price"] )) ? ", price      =  " . mysql_escape_string($values["price"])      . " " : "")
                     . ((isset($values["date_start"]) && !empty($values["date_start"])) ? ", date_start = '" . mysql_escape_string($values["date_start"]) . "'" : "")
                     . ((isset($values["date_end"]  ) && !empty($values["date_end"]  )) ? ", date_end   = '" . mysql_escape_string($values["date_end"])   . "'" : "")
                     . ((isset($values["recurring"] ) && !empty($values["recurring"] )) ? ", recurring   = '" . mysql_escape_string($values["recurring"]) . "'" : "")
                                                                                     . "  WHERE id   = '" . mysql_escape_string($values["id"]      ) . "'";
                $sql = preg_replace("/,/","SET",$sql,1);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $sql . "\n" . $status;
        }


        public static function delete($values)
        {
                global $database;
                global $databaseUnit4;
                global $auth;
                $sql = "DELETE FROM  orders WHERE id    = '" . mysql_escape_string($values["id"]      ) . "'";
                #print($sql);
                $status = $database->mutate($sql)
		        . $databaseUnit4->mutate($values);
                return $status;
        }
}

?>
