<? 
require_once(__DIR__ . "/../header.php");
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqlproducts.php";
require_once __DIR__ . "/../classes/fields/productTypes.php";

$grid = new Grid("ProductGroups");
$grid->datasource          = SqlProducts::query(Settings::ASGRID, "pp.parent=216"); 
$grid->fields["id"]        = new LinkButton("product.php?id={id}&type={type}");
$grid->fields["parent"]    = new HiddenField(array(def => 3));
$grid->fields["label"]     = new TextBox();
$grid->fields["info_uri"]  = new TextBox(); 
$grid->fields["recap_uri"] = new TextBox(); 
$grid->fields["enabled"]   = new BooleanField(); 
//$grid->fields["type"]      = new ProductTypesField();
//$grid->fields["recurring"] = new BooleanField();
//$grid->fields["price_1"]   = new TextBox();
//$grid->fields["price_3"]   = new TextBox();
//$grid->fields["price_6"]   = new TextBox();
//$grid->fields["price_12"]  = new TextBox();

$auth->setGridFilter("products",$grid,"owner");

print $grid->getHTML();

?>

</BODY>
</HTML>
