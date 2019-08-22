<?
require_once __DIR__ . "/generic/linkdropdownlist.php";
require_once __DIR__ . "/../settings.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlproducts.php";

class ProductsField extends LinkDropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$constraint       = NULL;
		if (is_array($param1) && !empty($param1["constraint"])) $constraint = $param1["constraint"];

		$this->datasource = new SqlProducts(Settings::ASCREATELIST, $constraint);
		$this->label      = "Product";
		$this->cellStyle  = "white-space: nowrap;";

		$this->viewField->url    = "/tables/product.php?id={product}";
		$this->viewField->name   = "product_label";
         }

}


?>
