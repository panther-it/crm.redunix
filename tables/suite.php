<? require_once __DIR__ . "/../header.php"; ?>
-&gt; <A HREF="suites.php">suites</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/grids/racks.php";
require_once __DIR__ . "/../classes/fields/datacenters.php";

$form= new Form("Suite");
if (isset($_GET["id"]))
{
    $form->datasource = SqlSuites::query(Settings::ASFORM, "id=" . $_GET["id"]);
    $form->fields["id"] = new LinkButton("suite.php?id={id}");
}
$form->fields["datacenter"] = new DatacentersField(); 
$form->fields["name"]       = new TextBox(); 
$form->fields["floor"]      = new TextBox(); 
print $form->getHTML();

if (isset($_GET["id"]))
{
    $tabs->Racks = new RacksGrid("suite = '" . addslashes($_GET["id"]) . "'");
    unset($tabs->Racks->fields["suite"]);
    print $tabs->getHTML();
}

?>

</BODY>
</HTML>
