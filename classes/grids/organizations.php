<?
require_once __DIR__ . "/generic.php";

class OrganizationsGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Organizations",$constraint);
		$this->fields["customer"] 	= new CustomersField(); 
		$this->fields["name"]  		= new TextBox();
		$this->fields["email"]     	= new LinkButton("mailto:{email}");
		$this->fields["phone"]      	= new TextBox();
		$this->fields["city"]         	= new TextBox(); 
        }
}
