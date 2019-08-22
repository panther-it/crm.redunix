<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlnameservers.php";

class NameServersField extends DropDownList 
{
	//public editField;
	//public viewField;

        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlNameServers();
		$this->label      = "NameServers";
		//$this->label = "Contact";

        }

}


?>
