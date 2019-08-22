<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../sql/sqlcoloaccess.php";

class ColoAccessesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource   = new SqlColoAccess();
		$this->label        = "Toegang";
		$this->style        = "width: 100px;";
        }

}


?>
