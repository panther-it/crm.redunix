<?
require_once(__DIR__ . "/settings.php");
require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/sql/sqlauthorization.php"); 
require_once(__DIR__ . "/sql/sqlcustomers.php"); 
require_once(__DIR__ . "/sql/sqldatacenters.php"); 
require_once(__DIR__ . "/sql/sqlsuites.php"); 
require_once(__DIR__ . "/sql/sqlracks.php"); 
require_once(__DIR__ . "/sql/sqldevices.php"); 
require_once(__DIR__ . "/sql/sqlcontacts.php"); 
require_once(__DIR__ . "/sql/sqlcoloaccess.php"); 
require_once(__DIR__ . "/sql/sqldomains.php"); 
require_once(__DIR__ . "/sql/sqlnameservers.php"); 
require_once(__DIR__ . "/sql/sqlcables.php"); 

class Authorization 
{
	const ADMIN_LEVEL     = 99;
	const ANONYMOUS_LEVEL =  0;
	const NOAUTH_LEVEL    = -1;

        function __construct() 
        {
		if (strpos($_SERVER["HTTP_VIA"],"www.produtch.nl") !== false)
		{	//alleen website bezoeker (guest) komt altijd via www.produtch.nl (proxy'ed) binnen
			//via algemene website = auto-logon
			if (strlen($this->username) == 0) $this->username = "guest";
			if (strlen($this->password) == 0) $this->password = "guest";
		}
		if ($_POST["class"] == "Login" || $_GET["class"] == "Login")
		{
			if (isset($_GET["username"] )) $this->username = $_GET["username"] ;
			if (isset($_GET["password"] )) $this->password = $_GET["password"] ;
			if (isset($_POST["username"])) $this->username = $_POST["username"];
			if (isset($_POST["password"])) $this->password = $_POST["password"];
		}
		if (isset($_GET["referrer"] )) $this->referrer = $_GET["referrer"] ;
        }

	public function __get($varname)
	{
		switch(strtolower($varname))
		{
			case "username":
				return $_SESSION["login_username"];
			case "password":
				return $_SESSION["login_password"];
			case "referrer":
				return $_SESSION["referrer"];
			case "contact":
				return $this->getContact();
			case "customer":
				return $this->getCustomer();
			case "tossi"   : //generate SSI tags (that the intermediate/frontend server should parse) 
				return $_SERVER["HTTP_PROXY_REQUEST"] == "SSI";
				//return $_SERVER["HTTP_ORIGINAL_URI"] != $_SERVER["REQUEST_URI"];
			case "viassi"  : //http request thru Apache SSI
				return strpos($_SERVER["HTTP_VIA"],"0.0") === 0;
			case "viaproxy": //http request thru a proxy-redirect-server
				return isset($_SERVER["HTTP_VIA"]); 
			case "valid":
				return !is_null($this->customer);
				//return !empty($_SESSION["login_contact"]) && !empty($_SESSION["login_customer"]);
			default:
		}
	}

	public function __set($varname,$value)
	{
		switch(strtolower($varname))
		{
			case "username":
				$_SESSION["login_username"] = $value;
				break;
			case "password":
				$_SESSION["login_password"] = $value;
				break;
			case "referrer":
				$cookieSettings = session_get_cookie_params();
				$value = preg_replace("/^.*" . $cookieSettings["domain"] . "/i","",$value); //make relative (drop domain part) because we are working multi-domain
				$_SESSION["referrer"]       = $value;
				break;
			case "contact":
				if (!$value) $value = NULL;
				$_SESSION["login_contact"]  = $value;
				break;
			case "customer":
				if (!$value) $value = NULL;
				$_SESSION["login_customer"] = $value;
				break;
			default:
		}
	}

        public function getContact()
        {
                global $database;
		$contact  = $_SESSION["login_contact"];
		$username = $this->username;
		$password = $this->password;

		if ((is_null($this->username)) 
                 || (is_null($this->password)))
			return NULL;

		if (!is_null($contact )) 
			if (($this->username == $contact->username) 
                         && ($this->password == $contact->password))
				return $contact ;

                $rs   = $database->query(SqlContacts::query(Settings::ASFORM, "     username='" . $this->username . "'"
                                                                            . " AND password='" . $this->password . "'"
                                                                            . " AND (c.owner = c.owner OR c.owner is NULL)  "));
		if (is_resource($rs))
		{
                	$contact  = mysql_fetch_object($rs);
			if ($contact ) 
			{ 
				$this->contact  = $contact; 
				return $contact ; 
			}
			else
				$this->logout();
		}
		error_log("Authorization: contact not found.");
		return NULL;
        }

        public function getCustomer()
        {
                global $database;
		$customer = $_SESSION["login_customer"];
		$contact  = $this->contact;

		if (is_null($contact)) 
			return NULL;

		if (!is_null($customer)) 
			if ($contact->customer == $customer->id) 
				return $customer;

                $rs   = $database->query(SqlCustomers::query(Settings::ASFORM, " id = " . $contact->customer
                                                                             . " AND (owner = owner OR owner is NULL)  "));
		if (is_resource($rs))
		{
                	$customer = mysql_fetch_object($rs);
			if ($customer) 
			{ 
				$this->customer = $customer; 
				return $customer; 
			}
			else
				$this->logout();
		}

		error_log("Authorization: Customer not found.");
		return NULL;
        }

	public function logout()
	{
		$this->customer = NULL;
		$this->contact  = NULL;
		$this->password = NULL;
		session_destroy();
		session_start();
		if (strpos($_SERVER["HTTP_REFERER"],"login.php") === false) $this->referrer = $_SERVER["HTTP_REFERER"];
		if (strpos($_SERVER["REQUEST_URI"] ,"login.php") === false) $this->referrer = $_SERVER["REQUEST_URI"];
	}

	public function redirect($target = FALSE)
	{
		if (!$target)       
		{ 
			$target = $this->referrer; 
			$this->referrer = NULL; 
		}
		else
		{
			$this->referrer = $_SERVER["REQUEST_URI"];
		}
		if (!isset($target) || strpos($target,"top.php")) $target = "/index.php";
		header("Location: " . $target);
	}


	public function allowed($section)
	{
		//$customerId = $this->customer->id;
		//get customers security level for $section
		$level      = $this->getLevel($section);

		if ($level == self::ADMIN_LEVEL)     return true;
		if ($level == self::ANONYMOUS_LEVEL) return true;
/*
		switch($section)
		{	//semi-restricted/anonymous, based upon ownership of data in the database tables
			case "customer":
			case "customers":
				return SqlCustomers::access($customerId);
			case "datacenter":
			case "datacenters":
				return SqlDatacenters::access($customerId);
			case "suite":
			case "suites":
				return SqlSuites::access($customerId);
			case "rack":
			case "racks":
				return SqlRacks::access($customerId);
			case "device":
			case "devices":
				return SqlDevices::access($customerId);
			case "coloaccess":
			case "coloaccesses":
			case "colo_accesses":
			case "colo_access":
				return SqlColoAccess::access($customerId);
			case "cable":
			case "cables":
				return SqlCables::access($customerId);
			case "contact":
			case "contacts":
				return SqlContacts::access($customerId);
			case "domain":
			case "domains":
				return SqlDomains::access($customerId);
			case "nameserver":
			case "nameservers":
				return SqlNameservers::access($customerId);
			default:
				//rest = no restriction (zo mag iedereen/anonymous beste een bestelling plaatsen via de webshop app)
				return true;
		}
*/
		return false; //self::NOAUTH_LEVEL
	}

	public function getLevel($section)
	{
		global $database;
		$customerId = $this->customer->id;
		$contactId  = $this->contact->id;
		$rs = $database->query(SqlAuthorization::query(Settings::ASLIST,"(customer=$customerId OR customer IS NULL) AND (contact=$contactId OR contact IS NULL) AND section='" . $section . "'"));
		if (!is_resource($rs)      ) return self::NOAUTH_LEVEL;
		if (mysql_num_rows($rs) < 1) return self::NOAUTH_LEVEL;
		$r  = mysql_fetch_object($rs);
		return $r->level;
	}


        static $ownerFields = array("customers" => "id" 
                                    ,"products" => "owner"
                                    ,"tasks"    => "owner"
				    //All others default to 'customer' (grid->fieldnames)
                                    );

	public function setGridFilter($section,$grid,$field = NULL)
	{
		$gridName = str_replace(" ","_",$grid->name);
		$section  = strtolower($section);

		if (empty($field))
			if (array_key_exists($section,self::$ownerFields))
				$field = self::$ownerFields[$section]; //predefined default
			else
				$field = "customer"; //default

		if ($this->getLevel($section) == Authorization::ADMIN_LEVEL)
		{
			if (@is_subclass_of($grid->datasource,"SqlCommon")
			&&  empty($grid->datasource->constraint))
			{
				if (!isset($_GET[$gridName]["filter"][$field])) 
					$grid->filter[$field] = $this->customer->id;
				else
					$grid->filter[$field] = $_GET[$gridName]["filter"][$field];
			}
		}
		else
			$grid->filter[$field] = $this->customer->id;
	}
}

$auth = new Authorization();
?>
