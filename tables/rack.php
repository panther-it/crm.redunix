<? require_once __DIR__ . "/../header.php"; ?>
-&gt; <A HREF="racks.php">racks</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/grids/devices.php";
require_once __DIR__ . "/../classes/fields/suites.php";
require_once __DIR__ . "/../classes/fields/rackAccessTypes.php";

$form= new Form("Rack");
if (isset($_GET["id"]))
{
    $form->datasource        = SqlRacks::query(Settings::ASFORM, "id = '" . $_GET["id"] . "'");
    $form->fields["id"]      = new LinkButton("rack.php?id={id}");
}
$form->fields["suite"]       = new SuitesField(); 
$form->fields["name"]        = new TextBox();
$form->fields["accesstype"]  = new RackAccessTypesField();
$form->fields["accesscode"]  = new TextBox();
print $form->getHTML();

if (isset($_GET["id"]))
{
    $tabs->Devices = new DevicesGrid("rack = '" . addslashes($_GET["id"]) . "'");
    unset($tabs->Devices->fields["rack"]);
    print $tabs->getHTML();
}
?>

</BODY>
</HTML>
