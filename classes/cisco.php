<?
require_once(__DIR__ . "/settings.php");
require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/telnet.php");
require_once(__DIR__ . "/sql/sqldevicemanagement.php"); 
require_once(__DIR__ . "/sql/sqldevices.php"); 
require_once(__DIR__ . "/sql/sqlcables.php"); 

class Cisco 
{
        function __construct($values) 
        {
		session_start();
	}

	private static function getManagement($sw)
	{
		global $database;
		$mgt = $database->query(SqlDeviceManagement::query(Settings::ASFORM
		 						  , "device_id = " . $sw->id
                                                                  . " AND type = 'telnet' "
								  )
				       );
		if ($mgt)
			if (mysql_num_rows($mgt) > 0)
				return mysql_fetch_object($mgt);

		return false;
       }


        private static function getSwitch($srv)
        {
		global $database;
		$pwr = $database->query(SqlCables::queryConnected($srv,"utp","switch"));

		if ($pwr)
			if (mysql_num_rows($pwr) >= 1) 
				return mysql_fetch_object($pwr);

		return false;
        }


        public static function action($switch = FALSE, $server,$action)
        {
		if (!$switch) $switch  = self::getSwitch($server["id"]);
		if ($switch)
		{
                	$mgt = self::getManagement($switch);
			if ($mgt)
				return self::execute($mgt->ip,$mgt->username,$mgt->password,$switch->port,$action, empty($server["label"]) ? $server["name"] : $server["label"]);
			else
				return "Unable to handle the switch.\n(DeviceManagement entry not found for " . $switch->label . ")";
		}
		else
			return "No network connection found";
        }


	private static function execute($ip,$usr,$pwd,$port,$action,$value=FALSE)
	{

		switch($action)
		{
			case "label":
				$mgt = new Telnet($ip);
				$status  = $mgt->cmd($usr);
				$status .= $mgt->cmd("enable");
				$status .= $mgt->cmd($pwd);
				$status .= $mgt->cmd("configure terminal");
				$status .= $mgt->cmd("interface $port");
				$status .= $mgt->cmd("description $value");
				$status .= $mgt->cmd("end");
				$status .= $mgt->cmd("write");
				$status .= $mgt->cmd("exit");
				return $status;
				break;
			default:
				return "Action '" . $action . "' not implemented.";
		}
	}
 

        public static function setLabel($switch = FALSE, $server)
        {
		$server = get_object_vars($server);
		return self::action($switch,$server,"label");
        }

}
?>
