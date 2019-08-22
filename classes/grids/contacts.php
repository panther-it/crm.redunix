<?
require_once __DIR__ . "/generic.php";

class ContactsGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Contacts",$constraint);
		$this->fields["customer"] 	= new CustomersField(); 
		$this->fields["organization"]  	= new OrganizationsField();
		$this->fields["organization"]->visible = false;	
		$this->fields["firstname"]     	= new TextBox();
		$this->fields["lastname"]      	= new TextBox();
		$this->fields["email"]         	= new LinkButton("mailto:{email}");
		$this->fields["phone"]         	= new LinkButton("callto:{phone}");
        }
}
