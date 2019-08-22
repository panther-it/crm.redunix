<?
require_once __DIR__ . "/generic/linkdropdownlist.php";
require_once __DIR__ . "/../sql/sqlcontacts.php";

class ContactsField extends LinkDropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlContacts();
		$this->defaultValue          = $auth->contact->id;
		$this->label      = "Contactpersoon";
		//$this->label = "Contact";
		$this->style                 = "width: 150px;";

		$this->viewField             = new LinkButton("/tables/contact.php?id={contact}");
		$this->viewField->name       = "contact_label";
		$this->viewField->label      = "{firstname} {lastname}";
		$this->viewField->parent     = get_class($this);
        }

}


?>
