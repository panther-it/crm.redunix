<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";

$grid = new Grid("DataCenters");
$grid->datasource = "SELECT id,name,address,contact FROM datacenters ORDER BY name";
$grid->fields["id"]      = new LinkButton("datacenter.php?id={id}");
$grid->fields["name"]    = new Textbox();;
$grid->fields["address"] = new Textbox();;
$grid->fields["contact"] = new Textbox();;
//$auth->setGridFilter("datacenters",$grid);

print $grid->getHTML();

?>

</BODY>
</HTML>
