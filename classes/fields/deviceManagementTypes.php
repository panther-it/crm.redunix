<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class DeviceManagementTypesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues("snmp=SNMP,ssh=SSH,telnet=Telnet,http=Web,ipmi1.5=IPMI v1.5,ipmi2.0=IPMI v2.0,drac=Dell DRAC,sim=SuperMicro SIM");
		$this->label      = "BeheerMethode";

        }

}


?>
