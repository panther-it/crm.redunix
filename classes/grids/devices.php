<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/deviceTypes.php";
require_once __DIR__ . "/../fields/racks.php";
require_once __DIR__ . "/../fields/customers.php";

class DevicesGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Devices",$constraint);
		$this->fields["label"]    = new TextBox();
		$this->fields["rack"]     = new RacksField(); 
		$this->fields["customer"] = new CustomersField(); 
		$this->fields["brand"]    = new TextBox();
		$this->fields["type"]     = new DeviceTypesField();
        }
}
