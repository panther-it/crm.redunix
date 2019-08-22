<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";
require_once __DIR__ . "/../grid.php";

class RackAccessTypesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->label      = "Toegangsbeleid";
		$this->datasource = new SqlStaticValues("key=Sleutel,code=Code,assistance=Assistentie");

        }

}


?>
