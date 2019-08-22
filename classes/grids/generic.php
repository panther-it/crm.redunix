<?
require_once __DIR__ . "/../settings.php";
require_once __DIR__ . "/../grid.php";
require_once __DIR__ . "/../fields/generic/linkbutton.php";

class GenericGrid extends Grid
{
        function __construct($name,$constraint=NULL)
        {
		global $auth;
		$this->showFilter        = false;
		$this->showInsert	 = false;
		$this->readonly		 = true;
                $name                    = strtolower($name);
                require_once(__DIR__ . "/../sql/sql${name}.php");
                $name                    = ucfirst($name);
                $datasource              = "Sql${name}";
		parent::__construct($name);
		$viewType = strpos($constraint,"=") + strpos($constraint," IS ") ? Settings::ASGRID : Settings::ASSEARCH;
		if (empty($constraint)) $viewType = Settings::ASGRID;
                $this->datasource        = new $datasource($viewType,$constraint); // call_user_func("Sql${name}::query",$viewType,$constraint);
                $this->fields["id"]      = new LinkButton(array("url"   => $this->formUrl . "?id={id}"
                                                               ,"label" => "{id}"));
		//$auth->setGridFilter($name,$this);
        }

	function __toString()
	{
		return "" . $this->getHTML();
	}
}
