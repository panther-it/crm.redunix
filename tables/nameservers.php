<? require_once __DIR__ . "/../header.php"; ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqlnameservers.php";
require_once __DIR__ . "/../classes/fields/customers.php";

$grid = new Grid("NameServers");
$grid->datasource         = SqlNameservers::query(Settings::ASGRID); 
$grid->fields["id"]       = new LinkButton("nameserver.php?id={id}");
$grid->fields["customer"] = new CustomersField();
$grid->fields["ns1"]      = new TextBox();
$grid->fields["ns2"]      = new TextBox();
$grid->fields["ns3"]      = new TextBox();



$auth->setGridFilter("nameservers",$grid);

print $grid->getHTML();

?>

</BODY>
</HTML>
