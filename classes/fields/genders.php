<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class gendersField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->label      = "Aanhef";
		$this->datasource = new SqlStaticValues("2=Dhr,3=Mevr,1=Onbekend");

        }

}


?>
