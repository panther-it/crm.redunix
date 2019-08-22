<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class DeviceTypesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues("server=server,router=router,switch=switch,powerswitch=powerswitch,firewall=firewall,nas=nas,serial switch=serial switch,fiberchannel switch=fiberchannel switch");
		$this->label      = "ApparaatType";

        }

}


?>
