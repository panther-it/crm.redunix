<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqlcoloaccess.php";
require_once __DIR__ . "/../classes/sql/sqlracks.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlaccessdevices.php";
require_once __DIR__ . "/../classes/fields/racks.php";
require_once __DIR__ . "/../classes/fields/accessdevices.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/contacts.php";

$grid                         = new Grid("ColoAccess");
$grid->datasource             = SqlColoAccess::query(Settings::ASGRID); 
$grid->formUrl                = "coloaccess.php";
$grid->fields["id"]           = new LinkButton("coloaccess.php?id={id}");
$grid->fields["rack"]         = new RacksField();
$grid->fields["customer"]     = new CustomersField(); 
$grid->fields["customer"]->javascript->onChange = "changeContact(this.options[this.selectedIndex].value);";
$grid->fields["contact"]      = new ContactsField(); 
$grid->fields["accessdevice"] = new AccessDevicesField(); 
//$grid->fields["rack"]->readonly         = true;
//$grid->fields["customer"]->readonly     = true;
//$grid->fields["accessdevice"]->readonly = true;
$auth->setGridFilter("coloaccess",$grid);
print $grid->getHTML();

?>

<SCRIPT LANGUAGE="javascript">
	function changeContact(newCustomer)
	{
		contactField = document.forms["ColoAccess"].elements["contact"];
		getFieldValues(contactField,"Contacts"   ,newCustomer);
	}
</SCRIPT>
</BODY>
</HTML>
