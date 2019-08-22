<? 
require_once(__DIR__ . "/../header.php");
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqldevices.php";
require_once __DIR__ . "/../classes/sql/sqldevicemanagement.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlracks.php";
require_once __DIR__ . "/../classes/fields/deviceTypes.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/racks.php";

$grid = new Grid("IPMI Power");
$grid->datasource         = SqlDevices::query(Settings::ASGRID, "id IN (SELECT device_id FROM device_management WHERE type='ipmi1.5' OR type='ipmi2.0')");
$grid->readonly           = true;
$grid->buttons    = Array ( new Button("Off")
                          , new Button("On")
                          , new Button("Reboot")
                          , new Button("Status")
			  );
$grid->fields["id"]       = new HiddenField();
$grid->fields["label"]    = new LinkButton("/tables/device.php?id={id}");
$grid->fields["rack"]     = new RacksField(); 
$grid->fields["customer"] = new CustomersField(); 
$grid->fields["brand"]    = new TextBox();
$grid->fields["type"]     = new DeviceTypesField();

$auth->setGridFilter("power",$grid);

print $grid->getHTML();

?>
</body>
</html>
