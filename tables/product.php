<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="products.php">ProductGroups</A>
</SPAN>
<? 
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/grids/products.php";
require_once __DIR__ . "/../classes/forms/productsProducts.php";
require_once __DIR__ . "/../classes/sql/sqlproducts.php";
require_once __DIR__ . "/../classes/fields/productTypes.php";
require_once __DIR__ . "/../classes/fields/generic/hidden.php";

$id   = $_GET["id"];
$type = $_GET["type"];
$form = new Form("name");

switch ($type)
{
	case "GROUP"  : $form->name = "ProductGroup"  ; break;
	case "PRODUCT": $form->name = "Product"       ; break;
	case "FEATURE": $form->name = "ProductFeature"; break;
	case "VALUE"  : $form->name = "FeatureValue"  ; break; 
}

if (isset($_GET["id"]))
{
    $form->datasource = SqlProducts::query(Settings::ASFORM, isset($id) ? "id=" . $id : FALSE);
    $form->fields["id"]    = new LinkButton("product.php?id={id}&type={type}");
}
$form->fields["label"]     = new TextBox();
$form->fields["info_uri"]  = new TextBox(); 
$form->fields["recap_uri"] = new TextBox(); 
$form->fields["enabled"]   = new BooleanField(); 
if ($type != "GROUP")
{
	$form->fields["recurring"] = new BooleanField();
	$form->fields["price_1"]   = new TextBox();
	$form->fields["price_3"]   = new TextBox();
	$form->fields["price_6"]   = new TextBox();
	$form->fields["price_12"]  = new TextBox();
}
print $form->getHTML();

if (isset($id)) 
{
    switch ($type)
    {
	case "GROUP":
		$tabs->Groups= new ProductsGrid("pp.parent=" . $id . " AND type='GROUP'");
		$tabs->Groups->name       = "ProductGroups";
		$tabs->Groups->readonly   = false;
		$tabs->Groups->showInsert = true ;
		$tabs->Groups->fields["parent"]    = new HiddenField(array(def => $id));
		unset($tabs->Groups->fields["owner"]);
		unset($tabs->Groups->fields["type"]);
		unset($tabs->Groups->fields["price_1"]);
		unset($tabs->Groups->fields["price_3"]);
		unset($tabs->Groups->fields["price_6"]);
		unset($tabs->Groups->fields["price_12"]);
		unset($tabs->Groups->fields["recurring"]);
	//unset($tabs->Products->fields["info_uri"]);
		//unset($tabs->Products->fields["recap_uri"]);
		$tabs->Products = new ProductsGrid("pp.parent=" . $id . " AND type='PRODUCT'");
		$tabs->Products->readonly   = false;
		$tabs->Products->showInsert = true ;
		$tabs->Products->fields["parent"]    = new HiddenField(array(def => $id));
		unset($tabs->Products->fields["owner"]);
		unset($tabs->Products->fields["type"]);
	case "PRODUCT":
		$tabs->Features = new ProductsGrid("pp.parent=" . $id . " AND type='FEATURE'");
		$tabs->Features->name       = "ProductFeatures";
		$tabs->Features->readonly   = false;
		$tabs->Features->showInsert = true ;
		$tabs->Features->fields["parent"]    = new HiddenField(array(def => $id));
		unset($tabs->Features->fields["owner"]);
		unset($tabs->Features->fields["type"]);
		$tabs->Koppeling = new ProductsProductsForm("type='FEATURE'");
		$tabs->Koppeling->fields["parent"]    = new HiddenField(array(def => $id));
		break;
	case "FEATURE":
		$tabs->Values= new ProductsGrid("pp.parent=" . $id . " AND type='VALUE'");
		$tabs->Values->name       = "FeatureValues";
		$tabs->Values->readonly   = false;
		$tabs->Values->showInsert = true ;
		$tabs->Values->fields["parent"]    = new HiddenField(array(def => $id));
		unset($tabs->Values->fields["owner"]);
		unset($tabs->Values->fields["type"]);
		unset($tabs->Values->fields["info_uri"]);
		unset($tabs->Values->fields["recap_uri"]);
		unset($tabs->Values->fields["recurring"]);
		break;
    }
    print $tabs->getHTML();
}

?>

</BODY>
</HTML>
