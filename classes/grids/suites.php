<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/datacenters.php";

class SuitesGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Suites",$constraint);
		$this->fields["datacenter"]   = new DatacentersField(); 
		$this->fields["name"]         = new TextBox();
		$this->fields["floor"]        = new TextBox();
        }
}
