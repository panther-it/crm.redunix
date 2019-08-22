<?
require_once __DIR__ . "/generic.php";

class CustomersGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Customers",$constraint);
		$this->fields["name"]           = new TextBox();
                $this->fields["state"]          = new CustomerStatesField();
        }
}
