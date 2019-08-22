<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="coloaccesses.php" CLASS="breadcrum">colo access</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqlcoloaccess.php";
require_once __DIR__ . "/../classes/sql/sqlracks.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlaccessdevices.php";
require_once __DIR__ . "/../classes/fields/racks.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/contacts.php";
require_once __DIR__ . "/../classes/fields/accessdevices.php";

$form= new Form("ColoAccess");
if (isset($_GET["id"]))
{
    $form->datasource         = SqlColoAccess::query(Settings::ASFORM, " id = '" . $_GET["id"] . "'");  
    $form->fields["id"]       = new LinkButton("coloaccess.php?id={id}");
    $form->submitButton->readonly = true;
}
$form->fields["rack"]         = new RacksField(); 
$form->fields["customer"]     = new CustomersField(); 
$form->fields["customer"]->javascript->onChange = "changeContact(this.options[this.selectedIndex].value);";
$form->fields["contact"]      = new ContactsField(); 
$form->fields["accessdevice"] = new AccessDevicesField(); 
print $form->getHTML();

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
