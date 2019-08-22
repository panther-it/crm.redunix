<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class ProductTypesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues("GROUP=Groep,PRODUCT=Product,FEATURE=Feature,VALUE=Value");
		$this->label      = "Soort";

        }

}


?>
