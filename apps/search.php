<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grids/domains.php";
require_once __DIR__ . "/../classes/grids/contacts.php";
require_once __DIR__ . "/../classes/grids/organizations.php";
require_once __DIR__ . "/../classes/grids/devices.php";
require_once __DIR__ . "/../classes/grids/orders.php";
require_once __DIR__ . "/../classes/grids/products.php";

$grids = array(new OrganizationsGrid($_GET["keyword"])
              ,new ContactsGrid($_GET["keyword"])
              ,new DomainsGrid($_GET["keyword"])
              ,new DevicesGrid($_GET["keyword"])
              ,new OrdersGrid($_GET["keyword"])
              ,new ProductsGrid($_GET["keyword"])
              );

foreach ($grids as $grid)
if ($auth->allowed($grid->name)) 
{ 
	$grid->showFilter        = false;
	$grid->showInsert	 = false;
	$grid->readonly		 = true;
	print $grid->getHTML();
}

require_once(__DIR__ . "/../footer.php");
?>

