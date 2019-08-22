<? require_once __DIR__ . "/../header.php"; ?>
-&gt; <A HREF="nameservers.php">NameServers</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/grids/domains.php";
require_once __DIR__ . "/../classes/sql/sqlnameservers.php";
require_once __DIR__ . "/../classes/fields/customers.php";

$id  = $_GET["id"];

$form= new Form("NameServer");
if (isset($_GET["id"]))
{
    $form->datasource     = SqlNameservers::query(Settings::ASFORM, "id=" . $id); 
    $form->fields["id"]   = new LinkButton("nameserver.php?id={id}");
}
$form->fields["customer"] = new CustomersField();
$form->fields["ns1"]      = new TextBox();
$form->fields["ns2"]      = new TextBox();
$form->fields["ns3"]      = new TextBox();
print $form->getHTML();

if (isset($_GET["id"]))
{
    $tabs->Domains = new DomainsGrid("nameservers = '" . addslashes($_GET["id"]) . "'");
    unset($tabs->Domains->fields["nameservers"]);
    print $tabs->getHTML();
}

?>

</BODY>
</HTML>
