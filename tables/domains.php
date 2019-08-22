<? require_once __DIR__ . "/../header.php"; ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grids/domains.php";

$grid = new DomainsGrid(); 
$grid->showFilter        = true;
$grid->showInsert	 = true;
$grid->readonly		 = false;
print $grid->getHTML();

?>

</BODY>
</HTML>
