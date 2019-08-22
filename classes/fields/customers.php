<?
require_once(__DIR__ . "/../authorization.php");
require_once __DIR__ . "/generic/linkdropdownlist.php";
require_once __DIR__ . "/../sql/sqlcustomers.php";

class CustomersField extends LinkDropDownList 
{
        function __construct($param1 = NULL)
        {
		global $auth;

                parent::__construct($param1);
		$this->datasource            = new SqlCustomers();
		$this->defaultValue          = $auth->customer->id;
		$this->label                 = "Klant";
		//$this->style                 = "width: 150px;";
		$this->cellStyle             = "white-space: nowrap;";
		//$this->readonly = true;

		$this->viewField->url        = "/tables/customer.php?id={customer}";
		$this->viewField->name       = "customer_label";

        }
}


?>
