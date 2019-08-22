<?
require_once __DIR__ . "/generic/dropdownlist.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../sql/sqlorganizations.php";

class OrganizationsField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
		global $auth;
                parent::__construct($param1);
		$this->datasource   = new SqlOrganizations();
		$this->label        = "Organizatie";
		$this->defaultValue = $auth->customer->organization;
        }

}


?>
