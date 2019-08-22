<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlcountries.php";

class CountriesField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
		$this->name = "country";
                parent::__construct($param1);
		$this->defaultValue = "NL";
		$this->label      = "Land";
		$this->datasource = new SqlCountries;
        }

}


?>
