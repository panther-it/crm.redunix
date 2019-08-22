<? require_once __DIR__ . "/../header.php"; ?>
-&gt; <A HREF="domains.php">domains</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/tabs.php";
require_once __DIR__ . "/../classes/sql/sqldomains.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/contacts.php";
require_once __DIR__ . "/../classes/fields/nameservers.php";
require_once __DIR__ . "/../classes/fields/organizations.php";
require_once __DIR__ . "/../classes/fields/generic/textbox.php";
require_once __DIR__ . "/../classes/fields/genders.php";
require_once __DIR__ . "/../classes/fields/countries.php";
require_once __DIR__ . "/../classes/fields/contactFunctions.php";

$form= new Form("Domain");
if (isset($_GET["id"]))
{
    $form->datasource        = SqlDomains::query(Settings::ASFORM,"d.id=" . $_GET["id"]); 
    $form->fields["id"]      = new HiddenField(); 
}
$form->fields["domainname"]  = new TextBox();
$form->fields["domainname"]->javascript->onChange = "checkDomainname(this)";
$form->fields["customer"]    = getCustomerField(); 
$form->fields["customer"]->javascript->onChange = "changeCustomer(this.selectedIndex);"
                                                . "changeContact(this.options[this.selectedIndex].value);";
$form->fields["adminc"]      = new ContactsField();
$form->fields["techc"]       = new ContactsField();
$form->fields["nameservers"] = new NameServersField();

$form->fields["customer"]->defaultValue = $auth->customer->id;
$form->fields["adminc"]->defaultValue   = $auth->contact->id;
$form->fields["techc"]->defaultValue    = $auth->contact->id;
print $form->getHTML();




$form= new Form("Contact");
$form->actions                  = "javascript:doRow(this); return false;";
$form->fields["customer"]       = getCustomerField();
$form->fields["organization"]   = new OrganizationsField();
$form->fields["firstname"]      = new TextBox();
$form->fields["lastname"]      	= new TextBox();
$form->fields["email"]          = new TextBox(); 
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
$tabs->New_Contact	        = $form->getHTML();


$form= new Form("NameServer");
$form->fields["customer"]    = getCustomerField();
$form->fields["ns1"]         = new TextBox();
$form->fields["ns2"]         = new TextBox();
$form->fields["ns3"]         = new TextBox();
$tabs->New_NameServers       = $form->getHTML();

print $tabs->getHTML();


function getCustomerField()
{
	global $auth;
	if ($auth->getLevel("domains") == Authorization::ADMIN_LEVEL)
		$field  = new CustomersField();
	else
		$field  = new HiddenField();
	$field->defaultValue = $auth->customer->id;
	return $field;
}
?>
<SCRIPT LANGUAGE="javascript">
	function changeCustomer(newIndex)
	{
		document.forms["NameServer"].elements["customer"].selectedIndex = newIndex;
		document.forms["Domain"].elements["customer"].selectedIndex = newIndex;
		document.forms["Contact"].elements["customer"].selectedIndex = newIndex;
	}

	function changeContact(newCustomer)
	{
		ownerField  = document.forms["Domain"].elements["customer"];
		techcField  = document.forms["Domain"].elements["adminc"];
		admincField = document.forms["Domain"].elements["techc"];
		nsField     = document.forms["Domain"].elements["nameservers"];
		getFieldValues(techcField,"Contacts"   ,newCustomer);
		getFieldValues(nsField   ,"NameServers",newCustomer);
	}

	function checkDomainname(field)
	{
		 setError(field,"No subdomains allowed",field.value.split(".").length > 2);
	}

</SCRIPT>
</BODY>
</HTML>
