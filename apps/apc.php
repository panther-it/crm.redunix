<? 
require_once(__DIR__ . "/../header.php");
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqldevices.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlracks.php";
require_once __DIR__ . "/../classes/fields/deviceTypes.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/racks.php";

$grid = new Grid("APC Power");
$grid->datasource         = SqlDevices::query(Settings::ASGRID, "id IN (" . SqlCables::constrainConnected("power","powerswitch") . ")"); 
$grid->readonly           = true;
$grid->showButtons        = Settings::ALWAYS;
$grid->buttons    = Array ( new Button(Array(label => "Off"   , type => "image", src => "/apps/style/img/poweroff.png"))
                          , new Button(Array(label => "On"    , type => "image", src => "/apps/style/img/poweron.png"))
                          , new Button(Array(label => "Reboot", type => "image", src => "/apps/style/img/powercycle.png"))
                          , new Button(Array(label => "Status", type => "image", src => "/apps/style/img/powerstatus.png"))
			  );
$grid->fields["id"]       = new HiddenField();
$grid->fields["label"]    = new LinkButton("/tables/device.php?id={id}");
$grid->fields["label"]->readonly=true;
$grid->fields["rack"]     = new RacksField(); 
$grid->fields["customer"] = new CustomersField(); 
$grid->fields["customer"]->readonly=true;
$grid->fields["brand"]    = new TextBox();
$grid->fields["type"]     = new DeviceTypesField();

$auth->setGridFilter("power",$grid);

print $grid->getHTML();

?>
</body>
</html>
