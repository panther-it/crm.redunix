<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlaccessdevices.php";

class AccessDevicesField extends DropDownList 
{
        //public $values;


        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->label      = "PasNr";
		$this->datasource = new SqlAccessDevices();
                

        }

}


?>
