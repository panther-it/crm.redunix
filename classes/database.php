<?
require_once __DIR__ . "/settings.php";

class Database
{

        protected $db;

        function __construct() 
        {
                self::connect();
        }


        public function connect($host = Settings::DB_HOST_CRM, $user = Settings::DB_USER_CRM, $pwd = Settings::DB_PWD_CRM, $db = Settings::DB_NAME_CRM)
        {
                $this->db = mysql_connect($host, $user, $pwd);
                mysql_select_db($db, $this->db);
        }


        public function query($sql)
        {
                $rs = mysql_query($sql,$this->db);
                if (!$rs)
		{
			error_log($sql . "; " . mysql_error($this->db));
                        return "Database error: " . mysql_error($this->db);
		}
                else
                        return $rs;

        }


        public function fetchObject($sql)
        {
                $rs = $this->query($sql);
                if (is_resource($rs))
                {
                        if (mysql_num_rows($rs) > 0)
                        {
                                $r  = mysql_fetch_object($rs);
                                return $r;
                        }
                }
                else
                {
                        return $rs; //$rs contains error_message
                }
        }
	public function fetchArray($sql) { return get_object_vars($this->fetchObject($sql)); }


        public function getList($sql)
        {
                $rs = $this->query($sql);
                if (is_resource($rs))
                {
			$list = "";
			while ($r = mysql_fetch_array($rs))
				$list .= $r[0] . "===" . $r[1] . "\n";
			return $list;
		}
                else
                {
                        return "error===" . $rs; //$rs contains error_message
                }
	}

        public function fetchIds($sql)
        {
                $rs = $this->query($sql);
                if (is_resource($rs))
                {
			$a = array();
			while ($r = mysql_fetch_array($rs))
				array_push($a,$r[0]);
			return $a;
		}
                else
                {
                        return $rs; //$rs contains error_message
                }
	}

         /*
         *
         * Description: execute a non-query sql statement (update,insert,delete)
         * Returns    : the status (nr row affected) or error reported by the mysql-server
         */
        public function mutate($sql)
        {

                $rs = mysql_query($sql,$this->db) or $result = mysql_error($this->db);
                //if (!isset($result) || empty($result)) $result = mysql_info($this->db); //else $result = $mysql_error();
                $result .= "\n" . mysql_info($this->db); //else $result = $mysql_error();
                #print("\n" . $result . "\n");
		error_log($sql . "; " . $result);

		if (stripos($sql,"INSERT") == 0)
		{
			$rs = $this->query("SELECT last_insert_id()"); 
			$r  = mysql_fetch_array($rs);
			return $r[0];
		}
		else
                	return $result;
        }


	public function buildConstraint($values)
	{
		$sql = "1=1 ";
		foreach ($values as $key => $value)
		{
			if (!empty($key) 
                        &&  !empty($value)
			&&  !in_array($key, array("class","action","insert","update","delete"))
			   )
				$sql .= "AND " . $key . "='" . mysql_escape_string($value) . "' "; 
		}

	}


        public function getValue($sql)
        {
                $rs = $this->query($sql);
                if (is_resource($rs))
                {
                        if (mysql_num_rows($rs) > 0)
                        {
                                $r  = mysql_fetch_array($rs);
                                return $r[0];
                        }
                }
                else
                {
                        return $rs; //$rs contains error_message
                }

        }


	public function getObject($table,$values)
	{
		$obj = $this->fetchObject("SELECT * FROM " . $table . " WHERE " . $this->buildConstraint($values));
		return $obj;
	}
}

$database = new Database();
?>
