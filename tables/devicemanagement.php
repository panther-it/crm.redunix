<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="devicemanagements.php" CLASS="breadcrum">Device Managements</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqldevicemanagement.php";
require_once __DIR__ . "/../classes/fields/devices.php";
require_once __DIR__ . "/../classes/fields/deviceManagementTypes.php";
require_once __DIR__ . "/../classes/fields/generic/hidden.php";

$form                         = new Form("DeviceManagement");
if (isset($_GET["id"])) 
{
	$form->datasource     = SqlDeviceManagement::query(Settings::ASFORM, " dm.id = '" . $_GET["id"] . "'");  
	$form->fields["id"]   = new HiddenField(); //LinkButton("devicemanagement.php?id={id}");
}
$form->fields["device"]       = new DevicesField();
$form->fields["type"]         = new DeviceManagementTypesField();
$form->fields["ip"]           = new TextBox();
$form->fields["username"]     = new TextBox();
$form->fields["password"]     = new TextBox();
print $form->getHTML();

?>

</BODY>
</HTML>
