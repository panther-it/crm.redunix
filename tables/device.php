<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="devices.php">devices</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/grids/cables.php";
require_once __DIR__ . "/../classes/grids/tasks.php";
require_once __DIR__ . "/../classes/grids/deviceManagements.php";
require_once __DIR__ . "/../classes/sql/sqldevices.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlracks.php";
require_once __DIR__ . "/../classes/sql/sqlcables.php";
require_once __DIR__ . "/../classes/sql/sqltasks.php";
require_once __DIR__ . "/../classes/fields/deviceTypes.php";
require_once __DIR__ . "/../classes/fields/cableTypes.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/racks.php";
require_once __DIR__ . "/../classes/fields/powerswitchport.php";
require_once __DIR__ . "/../classes/fields/switchport.php";
require_once __DIR__ . "/../classes/fields/contacts.php";
require_once __DIR__ . "/../classes/fields/generic/date.php";

$form= new Form("Device");
if (isset($_GET["id"]))
{
	$form->datasource   = SqlDevices::query(Settings::ASFORM, "id=" . $_GET["id"]);
	$form->fields["id"] = new LinkButton("device.php?id={id}");
}
$form->fields["name"]     = new TextBox();
$form->fields["label"]    = new TextBox();
$form->fields["customer"] = new CustomersField(); 
$form->fields["rack"]     = new RacksField(); 
$form->fields["position"] = new TextBox();
$form->fields["brand"]    = new TextBox();
$form->fields["type"]     = new DeviceTypesField();
if (!isset($_GET["id"]))
{
	$form->fields["rack"]->javascript->onChange = "changeRack(this.options[this.selectedIndex].value);";
	$form->fields["switch"]      = new SwitchPortField();
	$form->fields["powerswitch"] = new PowerswitchPortField();
}
print $form->getHTML();

$tabs->Connections = new CablesGrid($_GET["id"]);
$tabs->Tasks       = new TasksGrid("device = '" . addslashes($_GET["id"]) . "'");
$tabs->Management  = new DeviceManagementGrid("device_id = '" . addslashes($_GET["id"]) . "'");
print $tabs->getHTML();

?>

<SCRIPT LANGUAGE="javascript">
	function changeRack(newRack)
	{
		switchField = document.forms["Device"].elements["switch"];
		pwrswField  = document.forms["Device"].elements["powerswitch"];
		getFieldValues(switchField,"Devices",newRack);
		getFieldValues(pwrswField ,"Devices",newRack);
	}
</SCRIPT>
</BODY>
</HTML>
