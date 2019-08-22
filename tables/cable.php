<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqlcables.php";
require_once __DIR__ . "/../classes/sql/sqldevices.php";
require_once __DIR__ . "/../classes/fields/cableTypes.php";
require_once __DIR__ . "/../classes/fields/devices.php";

$form                         = new Form("Cables");
$form->datasource             = SqlCables::query(Settings::ASFORM,"id=" . $_GET["id"]); 
$form->fields["deviceA_id"]   = new DevicesField(); 
$form->fields["deviceA_port"] = new TextBox(); 
$form->fields["deviceB_id"]   = new DevicesField(); 
$form->fields["deviceB_port"] = new TextBox(); 
$form->fields["cableType"]    = new CableTypesField(); 
print $form->getHTML();

?>

</BODY>
</HTML>
