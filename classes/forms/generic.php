<?
require_once __DIR__ . "/../settings.php";
require_once __DIR__ . "/../form.php";
require_once __DIR__ . "/../fields/generic/linkbutton.php";

class GenericForm extends Form
{
        function __construct($name,$constraint=NULL)
        {
		global $auth;
		parent::__construct($name);
                $name                    	= strtolower($name);
		$sqlFile                        = __DIR__ . "/../sql/sql${name}s.php";
                if (file_exists($sqlFile)) require_once($sqlFile);
		if (!empty($constraint) && !strpos($constraint,"="))
			$constraint             = "id = '" . $constraint . "'";
		//$viewType = strpos($constraint,"=") ? Settings::ASGRID : Settings::ASSEARCH;
		//if (empty($constraint)) $viewType = Settings::ASGRID;
		if (!empty($constraint))
		{
                	$this->datasource     	= call_user_func("Sql" . $this->name . "s::query",Settings::ASFORM,$constraint);
                	$this->fields["id"]	= new LinkButton(array("url"       => "/tables/${name}.php?id={id}"
								      ,"readonly"  => true
								      ,"cellStyle" => "display: inline;"
								      ,"style"     => "display: inline;"));
		}
        }

	function __toString()
	{
		return "" . $this->getHTML();
	}
}
