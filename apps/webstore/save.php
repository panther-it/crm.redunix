<? require_once __DIR__ . "/../../header.php"; ?>

<!-- BREADCRUM PATH ------------------------------------------------------------------>
<div id="devide">
<A CLASS=""       HREF="group.php"       >Productoverzicht</A> &gt;
<A CLASS=""       HREF="product.php"     >Features &gt;</A>
<!--<A HREF="products.php?parent=<?= $parent ?>">Additionele Producten</A> &gt;-->
<A CLASS=""       HREF="customer.php"    >Klantgegevens &gt;</A>
<A CLASS="active" HREF="save.php"        >Bedankt</A>
</div>
<div id='homecontent'>

<? 
require_once __DIR__ . "/../../classes/sql/sqlorganizations.php";
require_once __DIR__ . "/../../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../../classes/sql/sqlcontacts.php";
require_once __DIR__ . "/../../classes/sql/sqlorders.php";
require_once __DIR__ . "/../../classes/database.php";

//$customer['firstname'] = $_GET["firstname"];
$_SESSION["klant"] = $_POST;

/*
$database->query(SqlProducts::insert($_SESSION["klant"]
                                    ,$_SESSION["order" ]
                                    ,$_SESSION["features"]
                                    ,$_SESSION["payment" ]
                                    );
*/

$status = "<PRE>Saving Customer...\n";
$_SESSION["klant"]["class"]        = "Customer"; 
$_SESSION["klant"]["action"]       = "insert"; 
$_SESSION["klant"]["country"]      = $_SESSION["klant"]["language"];
$_SESSION["klant"]["name"]         = $_SESSION["klant"]["organization_name"];
$_SESSION["klant"]["state"]        = 1; //TODO: what value?

if (strpos($auth->contact->organization_name, $_SESSION["klant"]["organization_name"]) === false)
{
	$status .= SqlOrganizations::insert($_SESSION["klant"]) . "\n\n";
	$_SESSION["klant"]["organization"] = $_SESSION["klant"]["id"]; 
	$status .= SqlCustomers::insert($_SESSION["klant"]) . "\n\n";
	$_SESSION["klant"]["customer"]     = $_SESSION["klant"]["id"]; 
	$_SESSION["klant"]["id"]           = $_SESSION["klant"]["organization"]; 
	$status .= SqlOrganizations::update($_SESSION["klant"]) . "\n\n"; //set organization
}
else
{
	$_SESSION["klant"]["organization"] = $auth->contact->organization;
	$_SESSION["klant"]["customer"]     = $auth->customer->id;
	$status .= "Using existing loggedin user.<BR/>\n";
}

$_SESSION["klant"]["owner"]        = $_SESSION["klant"]["customer"]; 
$status .= var_export($_SESSION["klant"],true);

if ($auth->contact->username != $_SESSION["klant"]["username"])
{
	$status .= SqlContacts::insert($_SESSION["klant"]) . "\n\n";
}

$status .= "OK<BR>\n";

$status .= "Logging in as customer...\n";
$auth->username = $_SESSION["klant"]["username"];
$auth->password = $_SESSION["klant"]["password"];
$status .= $auth->valid ? "OK<BR/>\n" : "FAILED\n";

if ($auth->valid)
{
$status .= "Saving Order...\n";
$_SESSION["order"]["customer"]        = $_SESSION["klant"]["owner"];
$_SESSION["order"]["enabled"]         = 0; 
$_SESSION["order"]["label"]          .= $_SESSION["features"]["266"]["label"]; //hostname"]["label"] . "." . $_SESSION["features"]["domainname"]["label"]; 
$_SESSION["order"]["date_start"]      = date("Y-m-d",strtotime("tomorrow")); 
$status .= var_export($_SESSION["order"],true);
$status .= SqlOrders::insert($_SESSION["order"]) . "\n\n";
$status .= "OK<BR>\n";

$status .= "Saving Features...\n";
$features = $_SESSION["features"];
foreach ($features as $feature)
{
	$feature["parent"]            = $_SESSION["order"]["id"];
        $feature["customer"]          = $_SESSION["klant"]["owner"];
        $feature["enabled"]           = 0; 
        $feature["date_start"]        = date("Y-m-d",strtotime("tomorrow")); 
	$status .= var_export($feature,true);
	$status .= SqlOrders::insert($feature) . "\n\n";
}
$status .= "OK</PRE>\n";
mail("sam@redunix.nl","Order: " . $_SESSION["order"]["label"], $status, "From: www.redunix.nl <orders@redunix.nl>");
?>
<!-- ------------------------------------------------------------------>
<center>
    <H1>Bedankt</H1>
    <P>Bedankt voor uw bestelling. Er wordt spoedig met u contact opgenomen betreffende oplevering van deze order.</P>
</center>
<?
}
else
{ ?>
<center>
    <H1>Error</H1>
    <P>Ongeldige logingegevens</P>
</center>
<? }
?>
</div>
<? 
error_log($status);
//print ($status);
require_once __DIR__ . "/../../footer.php"; ?>
