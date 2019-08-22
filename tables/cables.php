<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqlcables.php";
require_once __DIR__ . "/../classes/sql/sqldevices.php";
require_once __DIR__ . "/../classes/fields/devices.php";
require_once __DIR__ . "/../classes/fields/cableTypes.php";

$grid                         = new Grid("Cables");
$grid->datasource                  = SqlCables::query(Settings::ASGRID); 
$grid->fields["id"]           = new LinkButton("cable.php?id={id}");
$grid->fields["deviceA_id"]   = new DevicesField(); 
$grid->fields["deviceA_port"] = new TextBox(); 
$grid->fields["deviceB_id"]   = new DevicesField(); 
$grid->fields["deviceB_port"] = new TextBox(); 
$grid->fields["cableType"]    = new CableTypesField(); 
print $grid->getHTML();

?>

</BODY>
</HTML>
