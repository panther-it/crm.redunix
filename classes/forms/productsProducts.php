<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/products.php";
require_once __DIR__ . "/../fields/productTypes.php";

class ProductsProductsForm extends GenericForm
{
        function __construct($constraint = NULL)
        {
		parent::__construct("ProductFeatureLink");
		$this->fields["type"]      	= new ProductTypesField(); 
		$this->fields["type"]->javascript->onChange = "changeType(this.value);"; 
		$this->fields["parent"]      	= new ProductsField(); 
		$this->fields["child"]      	= new ProductsField(Array("constraint" => $constraint)); 
        }
}
?>
<SCRIPT LANGUAGE="javascript">
	function changeType(newType)
	{
		childField = document.forms["ProductFeatureLink"].elements["child"];
		getFieldValues(childField ,"Products",newType);
	}
</SCRIPT>

