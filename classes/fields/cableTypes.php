<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";
require_once __DIR__ . "/../grid.php";

class CableTypesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->label      = "KabelType";
		$this->datasource = new SqlStaticValues("power=power,utp=utp,fiber=fiber,serial=serial");

        }

}


?>
