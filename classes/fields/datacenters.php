<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqldatacenters.php";

class DatacentersField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlDatacenters();
		$this->label      = "DataCenter";

        }

}


?>
