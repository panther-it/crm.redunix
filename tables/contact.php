<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="contacts.php" CLASS="breadcrum">contacts</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqlcontacts.php";
require_once __DIR__ . "/../classes/fields/organizations.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/genders.php";
require_once __DIR__ . "/../classes/fields/countries.php";
require_once __DIR__ . "/../classes/fields/contactFunctions.php";

$form= new Form("Contact");
if (isset($_GET["id"]))
{
    $form->datasource      	= SqlContacts::query(Settings::ASFORM, "c.id = '" . $_GET["id"] . "'");
    $form->fields["id"]        	= new LinkButton("contact.php?id={id}");
}
if ($auth->getLevel("contacts") == Authorization::ADMIN_LEVEL)
{
    $form->fields["customer"]  	= new CustomersField(); 
//    $form->fields["customer"]->javascript->onChange = "changeOrganization(this.value)"; //TODO: make this work
}
else
    $form->fields["customer"]  	= new HiddenField(); 
$form->fields["customer"]->defaultValue = $auth->customer->id;
$form->fields["customer"]->javascript->onChange = "changeOrganization(this.options[this.selectedIndex].value);";
$form->fields["organization"]   = new OrganizationsField();
$form->fields["firstname"]      = new TextBox();
$form->fields["lastname"]      	= new TextBox();
$form->fields["fax"]      	= new TextBox();
$form->fields["phone"]      	= new TextBox();
$form->fields["phone_mobile"]   = new TextBox();
$form->fields["gender"]      	= new GendersField();
$form->fields["language"]      	= new CountriesField();
$form->fields["grafix_id"]      = new TextBox();
$form->fields["username"]      	= new TextBox();
$form->fields["password"]      	= new TextBox();
$form->fields["function"]  	= new ContactFunctionsField();
$form->fields["sidn_owner"]     = new TextBox();
$form->fields["sidn_contact"]   = new TextBox();
$form->fields["email"]     	= new LinkButton("mailto:{email}");

print $form->getHTML();

?>
<SCRIPT LANGUAGE="javascript">
	function changeOrganization(newCustomer)
	{
		orgField = document.forms["Contact"].elements["organization"];
		getFieldValues(orgField,"Organizations",newCustomer);
	}
</SCRIPT>

</BODY>
</HTML>
