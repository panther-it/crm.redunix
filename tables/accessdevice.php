<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="accessdevices.php" CLASS="breadcrum">accessdevices</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqlaccessdevices.php";
require_once __DIR__ . "/../classes/sql/sqlsuites.php";
require_once __DIR__ . "/../classes/sql/sqlstaticvalues.php";
require_once __DIR__ . "/../classes/fields/suites.php";

$form= new Form("AccessDevice");
if (isset($_GET["id"]))
{
    $form->datasource               = SqlAccessDevices::query(Settings::ASFORM, "id = '" . mysql_escape_string($_GET["id"]) . "'");
    $form->fields["id"]      	    = new HiddenField(); 
}
$form->fields["accesstype"]         = new DropDownList();
$form->fields["accesstype"]->datasource = new SqlStaticValues("outerkey=outerkey,rackkey=rackkey,card=card");
$form->fields["accessid"]           = new TextBox();
$form->fields["suite"]              = new SuitesField(); 
//$auth->setGridFilter("accessdevices",$form);
print $form->getHTML();

?>

</BODY>
</HTML>
