<?
require_once __DIR__ . "/generic.php";

class DomainsGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Domains",$constraint);
		$this->fields["customer"]   = new CustomersField();
		$this->fields["domainname"] = new TextBox();
		$this->fields["adminc"]     = new ContactsField();
        }
}
