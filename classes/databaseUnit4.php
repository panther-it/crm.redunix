<?
require_once __DIR__ . "/settings.php";
require_once __DIR__ . "/tools/xmlparser.php";

class DatabaseUnit4
{

        protected $db;
	protected $url;

        function __construct() 
        {
                self::connect();
        }


        public function connect($host = Settings::DB_HOST_Unit4, $user = Settings::DB_USER_Unit4, $pwd = Settings::DB_PWD_Unit4, $dir = Settings::DB_NAME_Unit4)
        {
		$this->url = $host . $dir;
                $this->db  = curl_init(); 
		curl_setopt($this->db,CURLOPT_USERPWD  , $user . ":" . $pwd); 
		//curl_setopt($this->db,CURLOPT_URL      , $host . $db       );
		curl_setopt($this->db,CURLOPT_POST     ,true               );
		curl_setopt($this->db,CURLOPT_USERAGENT,"REDUNIX CRM"      );
        }

        public function query($url,$params)
        {
		$url = preg_replace("/s$/","",$url);
                curl_setopt($this->db,CURLOPT_POSTFIELDS,$params);
                curl_setopt($this->db,CURLOPT_RETURNTRANSFER,"1" );
		curl_setopt($this->db,CURLOPT_URL       ,$this->url . $url . ".aspx");
		$rs = curl_exec($this->db);

		if (curl_getinfo($this->db,CURLINFO_HTTP_CODE) >= 500)
			return "Database error: " . $rs;
		else
		{
			$rs = new XMLParser($rs);
			return var_export($rs->getOutput(),true);
		}
        }

        public function fetchObject($params)
        {
                $rs = $this->query($params);
                if (is_array($rs))
			return $rs[0];
                else
                        return $rs; //$rs contains error_message

        }

/*
        public function getList($sql)
        {
	}

        public function fetchIds($sql)
        {
	}
*/

         /*
         *
         * Description: 
         * Returns    : 
         */
        public function mutate($values)
        {
		$url = preg_replace("/s$/","",$values["class"]);
		$params = $this->buildConstraint($values);
                curl_setopt($this->db,CURLOPT_POSTFIELDS    ,$params);
                curl_setopt($this->db,CURLOPT_CONNECTTIMEOUT,"10");
                curl_setopt($this->db,CURLOPT_RETURNTRANSFER,"1" );
                curl_setopt($this->db,CURLOPT_TIMEOUT       ,"20");
		curl_setopt($this->db,CURLOPT_URL           ,$this->url . $url . ".aspx");
		error_log($this->url . $url . ".aspx?" . $params . "\n");

                $rs = curl_exec($this->db);
		error_log($rs);

                if (curl_getinfo($this->db,CURLINFO_HTTP_CODE) >= 400)
			return $rs;
		else
		{
			if (XMLParser::isXML($rs))
			{
				$rs1 = new XMLParser($rs);
				$rs2 = $rs1->getOutput();
				if (empty($rs2)) return "Empty XML Response. " . $rs1->getStatus() . "\n" . $rs; //"Unknown XML Response from Unit4.";
				return var_export($rs2,true);
			}
			else
				return $rs;
		}
        }


	public function buildConstraint($values, $prepend = "")
	{
		$params = "1=1";
		foreach ($values as $key => $value)
		{
			if (!empty($key) 
                        &&  !empty($value)
			&&  !in_array($key, array("insert","update","delete"))
			   )
				$params .= "&" . $prepend . $key . "=" . urlencode($value); 
		}
		return $params;
	}
}

$databaseUnit4 = new DatabaseUnit4();
?>
