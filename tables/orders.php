<? 
require_once(__DIR__ . "/../header.php");
require_once __DIR__ . "/../classes/grids/orders.php";

$grid = new OrdersGrid("oo.parent=0");
$grid->showFilter        = true;
$grid->showInsert        = true;
$grid->readonly          = false;
print $grid;

?>

</BODY>
</HTML>
