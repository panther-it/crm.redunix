<? 
require_once __DIR__ . "/../../header.php";
require_once __DIR__ . "/../../classes/form.php";
require_once __DIR__ . "/../../classes/sql/sqlcontacts.php";
require_once __DIR__ . "/../../classes/sql/sqlproducts.php";
require_once __DIR__ . "/../../classes/database.php";
require_once __DIR__ . "/../../classes/fields/countries.php";
require_once __DIR__ . "/../../classes/fields/generic/all.php";

$_SESSION["order"]["recurring"] = $_POST["recurring"]["product"];
$_SESSION["order"]["price"]     = $_POST["recurring"]["price"];
$_SESSION["features"]  = $_POST["features"];
$customer = $auth->contact;

?>
<!-- BREADCRUM PATH ------------------------------------------------------------------>
<div id="devide">
<A CLASS=""         HREF="group.php"       >Productoverzicht</A> &gt;
<A CLASS=""         HREF="product.php"     >Features &gt;</A>
<!--<A HREF="products.php?parent=<?= $parent ?>">Additionele Producten</A> &gt;-->
<A CLASS="active"   HREF="customer.php"    >Klantgegevens &gt;</A>
<A CLASS="inactive" HREF="save.php"        >Bedankt</A>
</div>


<!-- ------------------------------------------------------------------>
<div id='homecontent'>
<center>
<?
$form= new Form("Persoonsgegevens");
$form->action = "save.php";
$form->submitButton->label   = "Verzenden";
$form->submitButton->onClick = "";
if ($auth->username != 'guest') $form->datasource      	= SqlContacts::query(Settings::ASFORM, "c.id = '" . $auth->contact->id . "'");
#if ($auth->getLevel("contacts") == Authorization::ADMIN_LEVEL)
#    $form->fields["customer"]  	= new CustomersField(); 
#else
#    $form->fields["customer"]  	= new HiddenField(); 
#$form->fields["customer"]->defaultValue = $auth->customer->id;

$form->fields["gender"]      	= new GendersField();
$form->fields["firstname"]      = new TextBox(array("label"=>"Voornaam"));
$form->fields["lastname"]      	= new TextBox(array("label"=>"Achternaam"));
$form->fields["organization_name"] = new TextBox(array("label"=>"Bedrijfsnaam"));
$form->fields["street"]      	= new TextBox(array("label"=>"Straat"));
$form->fields["zipcode"]        = new ZipcodeField(); 
$form->fields["city"]      	= new TextBox(array("label"=>"Stad"));
$form->fields["language"]      	= new CountriesField();
$form->fields["phone"]      	= new PhoneField();
$form->fields["fax"]      	= new PhoneField(array("label"=>"Fax"));
$form->fields["email"]          = new EmailField();
$form->fields["vatid"]          = new VATIDField();
$form->fields["username"]      	= new UsernameField();
$form->fields["password"]      	= new PasswordField();
$form->fields["function"]  	= new ContactFunctionsField();

print $form->getHTML();
?>
<div id="result"></div>
</center>
</div>
<? require_once __DIR__ . "/../../footer.php"; ?>
