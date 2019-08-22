<?
require_once __DIR__ . "/generic.php";

class TasksGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Tasks",$constraint);
		$this->fields["subject"]    = new TextBox();
		$this->fields["category"]   = new TextBox();
		$this->fields["date_end"]   = new DateField();
		$this->fields["customer"]   = new CustomersField();
		$this->fields["manager"]    = new ContactsField();
		$this->fields["executor"]   = new ContactsField();
		$this->fields["device"]     = new DevicesField();
		$this->fields["priority"]   = new TextBox();
		$this->fields["status"]     = new TextBox();
        }

}
