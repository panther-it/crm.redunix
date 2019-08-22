<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="organizations.php" CLASS="breadcrum">organizations</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/forms/organization.php";
require_once __DIR__ . "/../classes/grids/devices.php";
require_once __DIR__ . "/../classes/grids/coloaccess.php";
require_once __DIR__ . "/../classes/grids/domains.php";
require_once __DIR__ . "/../classes/grids/tasks.php";
require_once __DIR__ . "/../classes/grids/orders.php";
require_once __DIR__ . "/../classes/sql/sqlorganizations.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/countries.php";

$form= new OrganizationForm($_GET["id"]);
//print $form->getHTML();
print $form;

$tabs->Servers    = new DevicesGrid(   "  customer = '" . addslashes($form->fields["customer"]->value) . "'");
$tabs->ColoAccess = new ColoAccessGrid("  customer = '" . addslashes($form->fields["customer"]->value) . "'"); 
$tabs->Domains    = new DomainsGrid(   "  customer = '" . addslashes($form->fields["customer"]->value) . "'");
$tabs->Tasks      = new TasksGrid(     "  customer = '" . addslashes($form->fields["customer"]->value) . "'");
$tabs->Orders     = new OrdersGrid(    "o.customer = '" . addslashes($form->fields["customer"]->value) . "' AND (oo.parent=0 OR oo.parent IS NULL)");
print $tabs->getHTML();

?>

</BODY>
</HTML>
