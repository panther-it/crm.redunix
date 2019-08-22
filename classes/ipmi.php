<?
require_once(__DIR__ . "/settings.php");
require_once(__DIR__ . "/database.php");
require_once(__DIR__ . "/sql/sqlcustomers.php"); 
require_once(__DIR__ . "/sql/sqldevices.php"); 
require_once(__DIR__ . "/sql/sqldevicemanagement.php"); 
require_once(__DIR__ . "/sql/sqlcables.php"); 

class Power 
{
        function __construct($values) 
        {
		session_start();
	}

	private static function getManagement($values)
	{
		global $database;
		$rs = $database->query(SqlDeviceManagement::query(Settings::ASFORM
									      , "device_id = " . $values["id"] 
                                                                              . " AND(dm.type = 'ipmi1.5' "
								              . "  OR dm.type = 'ipmi2.0' "
								              . "    )"
									      )
						   );
		if (mysql_num_rows($rs) > 0)
		{
			$r = mysql_fetch_object($rs);
			return $r;
		}
		return false;
       }

	public static function execute($address,$username,$password,$cmd)
	{
		return shell_exec("/usr/bin/ipmitool -H $address -U $username -P $password $cmd");
	}

        public static function shutdown($values)
        {
                $mgt = self::getManagement($values);
		if ($mgt)
			return self::execute($mgt->ip,$mgt->username,$mgt->password,"chassis power off");
		else
			return "Remote Management not availible.";
        }

        public static function poweron($values)
        {
                $mgt = self::getManagement($values);
		if ($mgt)
			return self::execute($mgt->ip,$mgt->username,$mgt->password,"chassis power on");
		else
			return "Remote Management not availible.";
        }

        public static function reboot($values)
        {
                $mgt = self::getManagement($values);
		if ($mgt)
			return self::execute($mgt->ip,$mgt->username,$mgt->password,"chassis power cycle");
		else
			return "Remote Management not availible.";
        }

        public static function status($values)
        {
                $mgt = self::getManagement($values);
		if ($mgt)
			return self::execute($mgt->ip,$mgt->username,$mgt->password,"chassis power status");
		else
			return "Remote Management not availible.";
        }

}
?>
