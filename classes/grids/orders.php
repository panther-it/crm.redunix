<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/customers.php";
require_once __DIR__ . "/../fields/products.php";
require_once __DIR__ . "/../fields/generic/boolean.php";
require_once __DIR__ . "/../fields/recurringTypes.php";

class OrdersGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Orders",$constraint);
		$this->fields["customer"]   = new CustomersField();
		$this->fields["product"]    = new ProductsField(); 
		$this->fields["enabled"]    = new BooleanField(); 
		$this->fields["label"]      = new TextBox(); 
		$this->fields["price"]      = new TextBox(); 
		$this->fields["date_start"] = new TextBox(); 
		$this->fields["date_end"]   = new TextBox(); 
		$this->fields["recurring"]  = new RecurringTypesField(); 

        }
}
