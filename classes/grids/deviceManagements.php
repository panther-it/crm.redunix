<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/devices.php";
require_once __DIR__ . "/../fields/deviceManagementTypes.php";

class DeviceManagementGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("DeviceManagement",$constraint);
		$this->fields["device"]       = new DevicesField(); 
		$this->fields["type"]         = new DeviceManagementTypesField();
		$this->fields["ip"]           = new TextBox();
		$this->fields["username"]     = new TextBox();
		$this->fields["password"]     = new TextBox();
		$this->fields["device"]->readonly = true;
		$this->fields["type"]->readonly   = true;
        }
}
