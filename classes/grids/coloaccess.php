<?
require_once __DIR__ . "/generic.php";

class ColoAccessGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Coloaccess",$constraint);
		$this->fields["rack"]         = new RacksField();
		$this->fields["customer"]     = new CustomersField(); 
		$this->fields["contact"]      = new ContactsField(); 
		$this->fields["accessdevice"] = new AccessDevicesField(); 
        }
}
