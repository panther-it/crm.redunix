<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqldevicemanagement.php";
require_once __DIR__ . "/../classes/fields/deviceManagementTypes.php";
require_once __DIR__ . "/../classes/fields/devices.php";

$test                         = new Button("Test");
$grid                         = new Grid("DeviceManagements");
$grid->formUrl                = "/devicemanagement.php";
$grid->datasource             = SqlDeviceManagement::query(Settings::ASGRID); 
$grid->fields["id"]           = new LinkButton("devicemanagement.php?id={id}");
$grid->fields["device"]       = new DevicesField(); 
$grid->fields["type"]         = new DeviceManagementTypesField();
$grid->fields["ip"]           = new TextBox();
$grid->fields["username"]     = new TextBox();
$grid->fields["password"]     = new TextBox();
$grid->fields["device"]->readonly = true;
$grid->fields["type"]->readonly   = true;
//$auth->setGridFilter("devicemanagement",$grid);
array_push($grid->buttons,$test);
print $grid->getHTML();

?>

</BODY>
</HTML>
