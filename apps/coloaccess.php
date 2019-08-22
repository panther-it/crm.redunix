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
require_once __DIR__ . "/../classes/fields/datacenters.php";
require_once __DIR__ . "/../classes/fields/coloAccesses.php";
require_once __DIR__ . "/../classes/fields/accessdevices.php";
require_once __DIR__ . "/../classes/fields/generic/dates.php";
require_once __DIR__ . "/../classes/fields/generic/times.php";
require_once __DIR__ . "/../classes/fields/generic/textbox.php";

$form= new Form("RequestAccess");
$form->fields["datacenter"] = new DataCentersField();
$form->fields["datacenter"]->javascript->onChange = "changeColoAccesses(this.options[this.selectedIndex].value);";
$form->fields["accesscard"] = new ColoAccessesField();
$form->fields["date"]       = new DatesField();
$form->fields["time"]       = new TimesField();
$form->fields["duration"]   = new TimesField();
$form->fields["description"]= new TextBox();
print $form->getHTML();

?>
<SCRIPT LANGUAGE="javascript">
	function changeColoAccesses(newCustomer)
	{
		accesscardField = document.forms["requestAccess"].elements["accesscard"];
		getFieldValues(contactField,"Contacts"   ,newCustomer);
	}
</SCRIPT>
</BODY>
</HTML>
