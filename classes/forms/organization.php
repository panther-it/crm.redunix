<?
require_once __DIR__ . "/generic.php";
require_once __DIR__ . "/../fields/customers.php";
require_once __DIR__ . "/../fields/countries.php";

class OrganizationForm extends GenericForm
{
        function __construct($constraint = NULL)
        {
		global $auth;
		if (str_replace(" ","",$constraint) == "customer=''") $constraint = NULL; //form insert-mode when subform of customer-form
		parent::__construct("Organization",$constraint);

		$this->fields["name"]      	= new TextBox();
		$this->fields["email"]     	= new LinkButton("mailto:{email}");
		$this->fields["phone"]      	= new TextBox();
		$this->fields["fax"]      	= new TextBox();
		$this->fields["street"]      	= new TextBox();
		$this->fields["zipcode"]   	= new TextBox();
		$this->fields["city"]      	= new TextBox();
		$this->fields["country"]      	= new CountriesField();
		$this->fields["vatid"]  	= new TextBox();
		$this->fields["customer"]  	= new HiddenField(); 
		$this->fields["customer"]->defaultValue = $auth->customer->id;
        }
}
?>


