<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/rackAccessTypes.php";
require_once __DIR__ . "/../fields/suites.php";

class RacksGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Racks",$constraint);
		$this->fields["suite"]        = new SuitesField(); 
		$this->fields["name"]         = new TextBox();
		$this->fields["accesstype"]   = new RackAccessTypesField();
		$this->fields["accesscode"]   = new TextBox();
        }
}
