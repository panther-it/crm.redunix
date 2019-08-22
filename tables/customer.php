<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="customers.php" CLASS="breadcrum">customers</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/forms/organization.php";
require_once __DIR__ . "/../classes/grids/devices.php";
require_once __DIR__ . "/../classes/grids/coloaccess.php";
require_once __DIR__ . "/../classes/grids/domains.php";
require_once __DIR__ . "/../classes/grids/tasks.php";
require_once __DIR__ . "/../classes/grids/orders.php";
require_once __DIR__ . "/../classes/grids/contacts.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/fields/organizations.php";
require_once __DIR__ . "/../classes/fields/customerStates.php";

$form= new Form("Customer");
if (isset($_GET["id"]))
{
	$form->datasource     = SqlCustomers::query(Settings::ASFORM, "id = '" . $_GET["id"] . "'");
        $form->fields["id"  ] = new LinkButton("customer.php?id={id}");
}
$form->fields["name"        ] = new TextBox(); 
$form->fields["organization"] = new OrganizationsField(); 
$form->fields["organization"]->visible = false;
$form->fields["state"       ] = new CustomerStatesField(); 
$form->fields["bank_account"] = new TextBox(); 
print $form->getHTML();


$tabs->Contacts     = new ContactsGrid(    "  customer = '" . addslashes($_GET["id"]) . "'");
$tabs->Contacts->showInsert = true; 
$tabs->Contacts->fields["customer"]->visible           = false;
$tabs->Contacts->fields["customer"]->defaultValue      = $form->fields["id"]->value; //$_GET["id"];
$tabs->Contacts->fields["organization"]->defaultValue  = $form->fields["organization"]->value;
$tabs->Organization = new OrganizationForm("  customer = '" . addslashes($_GET["id"]) . "'");
$tabs->Servers      = new DevicesGrid(     "  customer = '" . addslashes($_GET["id"]) . "'");
$tabs->Severs->fields["customer"]->visible           = false;
$tabs->ColoAccess   = new ColoAccessGrid(  "  customer = '" . addslashes($_GET["id"]) . "'"); 
$tabs->ColoAccess->fields["customer"]->visible           = false;
$tabs->Domains      = new DomainsGrid(     "  customer = '" . addslashes($_GET["id"]) . "'");
$tabs->Tasks        = new TasksGrid(       "  owner    = '" . addslashes($_GET["id"]) . "'");
$tabs->Orders       = new OrdersGrid(      "o.customer = '" . addslashes($_GET["id"]) . "' AND oo.parent=0");
$tabs->Orders->fields["customer"]->visible       = false;
print $tabs->getHTML();
?>

</BODY>
</HTML>
