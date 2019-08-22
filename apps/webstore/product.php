<? 
require_once __DIR__ . "/../../header.php";
require_once __DIR__ . "/../../classes/sql/sqlproducts.php";
require_once __DIR__ . "/../../classes/database.php";

if (!empty($_GET["id"]))
	$product  = $database->fetchObject(SqlProducts::query(Settings::ASGRID, "p.id       = '" . $_GET["id"]                   . "' AND p.type in ('GROUP','PRODUCT')"));
if (!is_object($product) && !empty($_GET["recap_uri"])) 
	$product  = $database->fetchObject(SqlProducts::query(Settings::ASGRID, "p.recap_uri LIKE '%" . $_GET["recap_uri"]      . "%' AND p.type in ('GROUP','PRODUCT')"));
if (!is_object($product)) 
	$product  = $database->fetchObject(SqlProducts::query(Settings::ASGRID, "p.info_uri = '" . $_SERVER["HTTP_REFERER"]      . "' AND p.type in ('GROUP','PRODUCT')"));
if (!is_object($product)) 
	$product  = $database->fetchObject(SqlProducts::query(Settings::ASGRID, "p.id       = '" . $_SESSION["order"]["product"] . "' AND p.type in ('GROUP','PRODUCT')"));

$_SESSION["order"] = Array("product" => $product->id);

$features = $database->query(SqlProducts::query(Settings::ASGRID, "p.type = 'FEATURE' AND (pp.parent = " . $product->id . " OR pp.parent IN (select parent from products_products where child=" . $product->parent . ") OR pp.parent IN (select parent from products_products where child=" . $product->id . "))"));
$total    = round($product->price_12,2);

?>
	<SCRIPT language="javascript">
		var recurring      = 'J';

		function changePeriod(field)
		{
			total       = 0;
			prevPeriod  = recurring;
			recurring   = field.options[field.selectedIndex].value;
			spans       = document.getElementsByTagName("span");
			for(i=0; i < spans.length-1; i++)
			{
				price = parseFloat(spans[i].innerHTML);
				if (!isNaN(price))
				{
					//newPrice           =  price / prevPeriod * recurring;
					fieldName          =  spans[i].id.replace(/price/,"product");
					field              =  document.forms[0].elements[fieldName];
					field2             =  document.forms[0].elements[spans[i].id];
					if (!isNaN(field.selectedIndex)) 
						field      =  field.options[field.selectedIndex];
					newPrice           =  Math.round(parseFloat(field.getAttribute("price" + recurring))*100)/100;
					spans[i].innerHTML =  newPrice;
					field2.value       =  newPrice;
					total              += newPrice;
				}
			}
			totalField  = document.getElementById("total[price]");
			totalField.innerHTML = total; 
		}

		function setPrice(field)
		{
			price2Field = field.form.elements[field.name.replace(/product/,"price")]; 
			priceField  = document.getElementById(field.name.replace(/product/,"price"));
			prevPrice   = parseFloat(priceField.innerHTML); if (isNaN(prevPrice)) prevPrice = 0;
			newPrice    = Math.round(parseFloat(field.options[field.selectedIndex].getAttribute("price" + recurring))*100)/100;
			priceField.innerHTML = newPrice;
			price2Field.value = newPrice;
			totalField  = document.getElementById("total[price]");
			totalField.innerHTML = Math.round((parseFloat(totalField.innerHTML) - prevPrice + newPrice)*100)/100;
		}
	</SCRIPT>

<!-- BREADCRUM PATH ------------------------------------------------------------------>
<div id="devide">
<A CLASS=""         HREF="group.php"       >Productoverzicht</A> &gt;
<A CLASS="active"   HREF="product.php"     >Features &gt;</A>
<!--<A HREF="products.php?parent=<?= $parent ?>">Additionele Producten</A> &gt;-->
<A CLASS="inactive" HREF="customer.php"    >Klantgegevens &gt;</A>
<A CLASS="inactive" HREF="save.php"        >Bedankt</A>
</div>



<!-- ------------------------------------------------------------------>
<div id='homecontent'>
<form action="customer.php" method="post">
<input type="hidden" name="recurring[price]"   value="<?= round($product->price_12) ?>" />
<center>
    <H1><A href="<?= $product->info_uri ?>" target="_blank" label="meer info..."><?= $product->label ?></A></H1>
    <table class="form">
    <tr><th>Facturering</th>
        <td><select name="recurring[product]" onChange="changePeriod(this); setPrice(this)">
                <option value="M" priceM="<?= round($product->price_1 ,2) ?>"         >&euro; <?= round($product->price_1 ,2) ?>      per Maand     </option>
                <option value="K" priceK="<?= round($product->price_3 ,2) ?>"         >&euro; <?= round($product->price_3 ,2) ?> *  3 per Kwartaal  </option>
                <option value="H" priceH="<?= round($product->price_6 ,2) ?>"         >&euro; <?= round($product->price_6 ,2) ?> *  6 per Half-Jaar (5% korting)</option>
                <option value="J" priceJ="<?= round($product->price_12,2) ?>" selected>&euro; <?= round($product->price_12,2) ?> * 12 per Jaar      (10% korting)</option>
            </select>
        </td>
        <td nowrap>&euro; <span id="recurring[price]"><?= round($product->price_12,2) ?></td>
    </tr>
    <? while($feature = mysql_fetch_object($features)) 
       {
	   $defPrice = "";
           //$name   = str_replace(" ", "_", $feature->label);
	   $name   = $feature->id;
           $values = $database->query(SqlProducts::query(Settings::ASFORM, "pp.parent=" . $feature->id)); 
    ?>
    <tr>
        <th><?= $feature->label ?></th>
        <input type="hidden" name="features[<?= $name ?>][feature]" value="<?= $feature->id       ?>" />
        <input type="hidden" name="features[<?= $name ?>][price]"   value="<?= $feature->price_12 ?>" />
        <td><? if (mysql_num_rows($values) == 0 ) 
               {  
		   $defPrice = $feature->price_12;
            ?>
        	<input type="hidden" name="features[<?= $name ?>][product]" value="<?= $feature->id       ?>" 
                            priceM="<?= round($feature->price_1 ,2) ?>"
                            priceK="<?= round($feature->price_3 ,2) ?>"
                            priceH="<?= round($feature->price_6 ,2) ?>"
                            priceJ="<?= round($feature->price_12,2) ?>" />
                 <input type="text" name="features[<?= $name ?>][label]" value="" />
            <? } else {  ?>
                 <select name="features[<?= $name ?>][product]" onChange="setPrice(this)">
                     <? while ($value = mysql_fetch_object($values)) 
                        {
				if (strlen($defPrice) == 0)
					$defPrice = $value->price_12;
                     ?>
                     <option value="<?= $value->id ?>" 
                            priceM="<?= round($value->price_1 ,2) ?>"
                            priceK="<?= round($value->price_3 ,2) ?>"
                            priceH="<?= round($value->price_6 ,2) ?>"
                            priceJ="<?= round($value->price_12,2) ?>" ><?= $value->label ?></option>
                     <? } ?>
                 </select>
            <? } 
	   $total += round($defPrice,2);
	?>
        </td>
        <td nowrap>&euro; <span id="features[<?= $name ?>][price]"><?= round($defPrice,2) ?></span></td>
    </tr>
    <? } ?>
    <tr><th>Total</th><td></td><td>&euro; <span id="total[price]"><?= $total ?></span></td></tr>
    </table>
    <input type="submit" value="Volgende &gt;&gt;" />
</center>
</form>
</div>
<? require_once __DIR__ . "/../../footer.php"; ?>
