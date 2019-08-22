<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlracks.php";

class RacksField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->cellStyle  = "width: 150px;";
		$this->label      = "Rack";
		$this->datasource = new SqlRacks();

        }

}


?>
