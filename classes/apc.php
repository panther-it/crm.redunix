<?
require_once(__DIR__ . "/settings.php");
require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/sql/sqldevicemanagement.php"); 
require_once(__DIR__ . "/sql/sqldevices.php"); 
require_once(__DIR__ . "/sql/sqlcables.php"); 

class Power 
{
	const STATUS           = 90;
	const CONFIGNAME       = 91;
	const IMMEDIATE_ON     =  1;
	const IMMEDIATE_OFF    =  2;
	const IMMEDIATE_REBOOT =  3;
	const DELAYED_OFF      =  5;
	const TEST             =  6;
	const CANCEL_PENDING   =  7;

        function __construct($values) 
        {
		session_start();
	}

	private static function getManagement($pwr)
	{
		global $database;
		$mgt = $database->query(SqlDeviceManagement::query(Settings::ASFORM
		 						  , "dm.device_id = " . $pwr->id
                                                                  . " AND dm.type = 'snmp' "
								  )
				       );
		if (is_resource($mgt))
			if (mysql_num_rows($mgt) > 0)
				return mysql_fetch_object($mgt);

		return false;
       }


        private static function getPowerSwitch($srv)
        {
		global $database;
		$pwr = $database->query(SqlCables::queryConnected($srv,"power","powerswitch"));

		if (is_resource($pwr))
		{
			if (mysql_num_rows($pwr) >= 1) 
				return mysql_fetch_object($pwr);
		}
		else
			return $pwr; //error message

		return false;
        }


        public static function action($switch = FALSE,$server,$action)
        {
		if (is_object($server)) $server = get_object_vars($server); //convert to array
		if (!$switch) $switch  = self::getPowerSwitch($server["id"]);
		if (is_object($switch) || is_array($switch))
		{
                	$mgt = self::getManagement($switch);
			if ($mgt)
				return self::execute($mgt->ip,$mgt->username,$mgt->password,$switch->port,$action, empty($server["label"]) ? $server["name"] : $server["label"]);
			else
				return "Unable to handle the Powerswitch.\n(DeviceManagement entry not found for " . $switch->label . ")";
		}
		else
			return "No powerswitch connection found: " . $switch;
        }


	private static function execute($ip,$usr,$pwd,$port,$action,$value=FALSE)
	{
		switch($action)
		{
			case self::STATUS:
				$retVal = snmpget($ip,"public","PowerNet-MIB::rPDUOutletStatusOutletState." . $port);
				break;
			case self::TEST:
				$retVal = snmpget($ip,"public",".1.3.6.1.2.1.1.1.0");
			case self::IMMEDIATE_ON:
			case self::IMMEDIATE_OFF:
			case self::IMMEDIATE_REBOOT:
				//return snmpset($ip,"public","PowerNet-MIB::rPDUOutletControlOutletCommand." . $port,"i",$action) == 1 ? "Control successfull" : "Control failed";
				return snmpset($ip,"public",".1.3.6.1.4.1.318.1.1.12.3.3.1.1.4." . $port,"i",$action) == 1 ? "Control successfull" : "Control failed";
			case self::CONFIGNAME:
				//return snmpset($ip,"public","PowerNet-MIB::rPDUOutletConfigOutletName."     . $port,"s",$value ) == 1 ? "OutletName set!" : "OutletName set Failed on $ip";
				return snmpset($ip,"public",".1.3.6.1.4.1.318.1.1.12.3.4.1.1.2." . $port,"s",$value ) == 1 ? "OutletName set!" : "OutletName set Failed on $ip";
			default:
				return "Action '" . $action . "' not implemented.";
		}
		if ($retVal !== false) return $retVal; else return "ERROR: SNMP Connection timeout.";
	}

	public static function addUser($switch,$contact)
 	{
		if (empty($contact->username) || empty($contact->password)) 
			return "Error: Empty username/password for '" . $contact->firstname . " " . $contact->lastname . "' (" . $contact->id . ")";
		$mgt = self::getManagement($switch);
		if ($mgt)
		{
			$sw = new Telnet($mgt->ip);
			$result .= $sw->cmd($mgt->username);
			$result .= $sw->cmd($mgt->password);
			$result .= $sw->cmd(3);
			$result .= $sw->cmd(1);
			$result .= $sw->cmd(3);
			$result .= $sw->cmd(3); //delete user
			$result .= $sw->cmd($contact->username);
			$result .= $sw->cmd("YES");
			$result .= $sw->cmd("");
			$result .= $sw->cmd(1); //create user
			$result .= $sw->cmd($contact->username);
			$result .= $sw->cmd($contact->password);
			$result .= $sw->cmd($contact->password);
			$result .= $sw->cmd($contact->organization_name . " - " . $contact->firstname . " " . $contact->lastname);
			$result .= $sw->cmd("");
			$result .= $sw->cmd(6); //edit users outlet access
			$result .= $sw->cmd($contact->username);
			$result .= $sw->cmd($switch->port);
			$result .= $sw->cmd("");
			return $result;
		}
		else
			return "Unable to handle the Powerswitch.\n(DeviceManagement Telnet entry not found for " . $switch->label . ")";
	}

        public static function reboot($values)
        {
		return self::action(FALSE,$values,self::IMMEDIATE_REBOOT);
        }


        public static function shutdown($values)
        {
		return self::action(FALSE,$values,self::IMMEDIATE_OFF);
        }


        public static function test($values)
        {
		//return self::action(FALSE,$values,self::TEST);
		return snmpget($values["ip"],"public",".1.3.6.1.2.1.1.1.0");
        }

        public static function poweron($values)
        {
		return self::action(FALSE,$values,self::IMMEDIATE_ON);
        }

        public static function status($values)
        {
		return self::action(FALSE,$values,self::STATUS);
        }

        public static function setLabel($switch,$server)
        {
		return self::action($switch,$server,self::CONFIGNAME);
        }

}
?>
