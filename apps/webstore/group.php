<? 
require_once __DIR__ . "/../../header.php";
require_once __DIR__ . "/../../classes/sql/sqlproducts.php";
require_once __DIR__ . "/../../classes/database.php";
require_once __DIR__ . "/../../tables/webparts/block.php";

$group  = $_GET["id"];
$_SESSION["group"] = $group;
if (empty($group)) $group = 216; //hoofddir

$products = $database->query(SqlProducts::query(Settings::ASGRID, "pp.parent=" . $group . " AND p.type in ('PRODUCT','GROUP') AND enabled=true"));

?>

<!-- BREADCRUM PATH ------------------------------------------------------------------>
<div id="devide">
<A CLASS="active"   HREF="group.php"       >Productoverzicht</A> &gt;
<A CLASS="inactive" HREF="product.php"     >Features &gt;</A>
<!--<A HREF="products.php?parent=<?= $parent ?>">Additionele Producten</A> &gt;-->
<A CLASS="inactive" HREF="customer.php"    >Klantgegevens &gt;</A>
<A CLASS="inactive" HREF="save.php"        >Bedankt</A>
</div>

<!-- ------------------------------------------------------------------>
<div id="products">
<? 
while ($product = mysql_fetch_object($products)) 
{ 
	$href = strtolower($product->type) . ".php?id=" . $product->id;
	if (!empty($product->info_uri))
		$href = $product->info_uri;
	block($href, $product->label, $product->recap_uri); 
} 
?>
</div>

<? require_once __DIR__ . "/../../footer.php"; ?>
