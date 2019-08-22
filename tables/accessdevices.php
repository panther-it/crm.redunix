<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqlaccessdevices.php";
require_once __DIR__ . "/../classes/sql/sqlstaticvalues.php";
require_once __DIR__ . "/../classes/sql/sqlsuites.php";
require_once __DIR__ . "/../classes/fields/suites.php";

$grid                               = new Grid("AccessDevices");
$grid->datasource                   = SqlAccessDevices::query(Settings::ASGRID); 
$grid->fields["id"]                 = new LinkButton("accessdevice.php?id={id}");
$grid->fields["accesstype"]         = new DropDownList();
$grid->fields["accesstype"]->datasource = new SqlStaticValues("outerkey=outerkey,rackkey=rackkey,card=card");
$grid->fields["accessid"]           = new TextBox();
$grid->fields["suite"]              = new SuitesField(); 
//$auth->setGridFilter("accessdevices",$grid);
print $grid->getHTML();

?>

</BODY>
</HTML>
