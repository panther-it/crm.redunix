<? 
require_once(__DIR__ . "/../header.php");
require_once(__DIR__ . "/../classes/grids/devices.php");

$grid = new DevicesGrid(); 
$grid->showFilter        = true;
$grid->showInsert	 = true;
$grid->readonly		 = false;
print $grid->getHTML();

?>

</BODY>
</HTML>
