<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class ContactFunctionsField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues("1=Technisch,2=Financieel,3=Overig,4=Klant");
		$this->label      = "PersoneelsFunctie";

        }

}


?>
