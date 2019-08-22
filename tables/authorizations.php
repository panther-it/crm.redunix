<? require_once(__DIR__ . "/../header.php"); ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqlcustomers.php";
require_once __DIR__ . "/../classes/sql/sqlauthorization.php";
require_once __DIR__ . "/../classes/sql/sqlstaticvalues.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/contacts.php";

$grid                         = new Grid("Authorization");
$grid->datasource             = SqlAuthorization::query(Settings::ASGRID); 
$grid->fields["id"]           = new LinkButton("authorization.php?id={id}");
$grid->fields["customer"]     = new CustomersField(); 
$grid->fields["contact"]      = new ContactsField(); 
$grid->fields["section"]      = new TextBox(); 
$grid->fields["level"]        = new DropDownList(); 
$grid->fields["level"]->datasource = new SqlStaticValues("99=ADMIN,100=NO ACCESS,0=ANONYMOUS,1=1,2=2,3=3,4=4,5=5,6=6,7=7,8=8,9=9");
$auth->setGridFilter("authorization",$grid);
print $grid->getHTML();

?>

</BODY>
</HTML>
