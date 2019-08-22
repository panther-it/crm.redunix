<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class CustomerStatesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues("1=Onbekend,2=Actief,3=Blocked,4=Geannuleerd,5=Nieuw");
		$this->label      = "Status";

        }

}


?>
