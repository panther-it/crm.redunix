<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlstaticvalues.php";

class RecurringTypesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues("M=Maandelijks,K=per Kwartaal,H=Half jaarlijks,J=Jaarlijks,E=Eenmalig");
		$this->label      = "Abonnement";
        }

}


?>
