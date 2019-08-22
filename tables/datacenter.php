<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/grids/suites.php";

$form= new Form("DataCenter");
if (isset($_GET["id"]))
{
    $form->datasource       = SqlDataCenters::query(Settings::ASFORM, "id = '" . $_GET["id"] . "'");
    $form->fields["id"]     = new LinkButton("datacenter.php?id={id}");
}
$form->fields["name"]       = new Textbox();
$form->fields["address"]    = new Textbox();
$form->fields["contact"]    = new Textbox();
$form->fields["coords"]     = new Textbox();
$form->fields["accesstype"] = new AccessTypesField();
print $form->getHTML();

if (isset($_GET["id"]))
{
    $tabs->Suites   = new SuitesGrid("datacenter = '" . addslashes($_GET["id"]) . "'");
    unset($tabs->Suites->fields["datacenter"]);
    print $tabs->getHTML();

}
?>

</BODY>
</HTML>
