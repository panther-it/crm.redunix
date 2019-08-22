<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="orders.php">Orders</A>
</SPAN>
<? 
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqlorders.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/products.php";
require_once __DIR__ . "/../classes/fields/recurringTypes.php";
require_once __DIR__ . "/../classes/fields/generic/hidden.php";

$id         = $_GET["id"];
$date_start = $_GET["date_start"];

$form= new Form("Order");
if (isset($_GET["id"]))
{
    $form->datasource = SqlOrders::query(Settings::ASFORM, isset($id) ? "o.id=" . $id : FALSE);
    $form->fields["id"]    = new LinkButton("order.php?id={id}&date_start={date_start}");
}
$form->fields["customer"]  = new CustomersField(); 
$form->fields["product"]   = new ProductsField(); 
$form->fields["enabled"]   = new BooleanField(); 
$form->fields["label"]     = new TextBox();
$form->fields["price"]     = new TextBox();
$form->fields["recurring"] = new RecurringTypesField();
$form->fields["date_start"]= new TextBox();
$form->fields["date_end"]  = new TextBox();
print $form->getHTML();

if (isset($id))
{
	$grid= new Grid("SubOrders");
	$grid->datasource = SqlOrders::query(Settings::ASGRID, isset($id) ? "oo.parent=" . $id : FALSE);
	$grid->fields["parent"]    = new HiddenField(array(def => $id));
	$grid->fields["customer"]  = new HiddenField(array(def => $auth->customer->id));
	$grid->fields["id"]        = new LinkButton("order.php?id={id}&date_start={date_start}");
	$grid->fields["product"]   = new ProductsField(); 
	$grid->fields["enabled"]   = new BooleanField();
	$grid->fields["label"]     = new TextBox();
	$grid->fields["price"]     = new TextBox();
	$grid->fields["date_start"]= new TextBox(array(def => $date_start));
	$grid->fields["date_end"]  = new TextBox();
	print $grid->getHTML();
}
?>

</BODY>
</HTML>
