<? 
require_once(__DIR__ . "/../header.php");
require_once __DIR__ . "/../classes/sql/sqlorganizations.php";
require_once __DIR__ . "/../classes/sql/sqlcontacts.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlproducts.php";
require_once __DIR__ . "/../classes/sql/sqlorders.php";
require_once __DIR__ . "/../classes/sql/sqlcountries.php";

/*
$sqlCountries = new SqlCountries();
$rs = $database->query(SqlOrganizations::query(Settings::ASFORM));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	print "<PRE>";
	print $sqlCountries->insert($r["country"]);
	print "</PRE>";
	$r["action"] = "insert";
	$r["class"]  = "Organization";
	print "<PRE>";
	print_r($r);
	//print SqlOrganizations::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}


$rs = $database->query(SqlContacts::query(Settings::ASFORM));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "Contact";
	print "<PRE>";
	print_r($r);
	//print SqlContacts::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}


$rs = $database->query(SqlCustomers::query(Settings::ASFORM));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "Customer";
	print "<PRE>";
	print_r($r);
	//print SqlCustomers::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}



$rs = $database->query(SqlProducts::query(Settings::ASFORM,"p.type='GROUP'"));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "ProductGroup";
	print "<PRE>";
	print_r($r);
	//print SqlProducts::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}

$rs = $database->query(SqlProducts::query(Settings::ASFORM,"p.type='PRODUCT' AND p.recurring=1"));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "Product";
	print "<PRE>";
	print_r($r);
	//print SqlProducts::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}

$rs = $database->query(SqlProducts::query(Settings::ASFORM,"p.type='FEATURE'"));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "Product";
	print "<PRE>";
	print_r($r);
	//print SqlProducts::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}
*/

$rs = $database->query(SqlProducts::query(Settings::ASFORM,"p.type='VALUE'"));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "Product";
	print "<PRE>";
	print_r($r);
	//print SqlProducts::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();
}

/*
$rs = $database->query(SqlOrders::query(Settings::ASFORM,"o.id < 8292"));
print mysql_num_rows($rs);
while($r = mysql_fetch_array($rs))
{
	$r["action"] = "insert";
	$r["class"]  = "ProductComponent";
	print "<PRE>";
	print_r($r);
	//print SqlProducts::insert($r); //local db will give 'duplicate entry', but unit4 db will insert
	print $databaseUnit4->mutate($r) . "\n"; 
	$r["class"]  = "Subscription";
	print $databaseUnit4->mutate($r) . "\n"; 
	print "</PRE>";
	print "<HR />";
	ob_flush(); flush();

}
*/
?>
</BODY>
</HTML>
