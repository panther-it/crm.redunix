<? require_once __DIR__ . "/../header.php"; ?>
</SPAN>
<?
require_once __DIR__ . "/../classes/grid.php";
require_once __DIR__ . "/../classes/sql/sqltasks.php";
require_once __DIR__ . "/../classes/fields/generic/textbox.php";
require_once __DIR__ . "/../classes/fields/generic/date.php";
require_once __DIR__ . "/../classes/fields/generic/linkbutton.php";
require_once __DIR__ . "/../classes/fields/contacts.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/devices.php";
require_once __DIR__ . "/../classes/fields/generic/hidden.php";

$grid = new Grid("Tasks");
$grid->datasource = SqlTasks::query(Settings::ASGRID); 
$grid->fields["id"]         = new LinkButton("task.php?id={id}");
$grid->fields["subject"]    = new TextBox();
$grid->fields["category"]   = new TextBox();
$grid->fields["date_end"]   = new DateField();
$grid->fields["customer"]   = new CustomersField();
$grid->fields["manager"]    = new ContactsField();
$grid->fields["executor"]   = new ContactsField();
$grid->fields["device"]     = new DevicesField();
$grid->fields["priority"]   = new TextBox();
$grid->fields["status"]     = new TextBox();
//$auth->setGridFilter("tasks",$grid);


/* Default Filter */
if (!isset($_GET[$grid->name]["filter"]))
	$grid->filter["executor"]   = $auth->contact->id;


print $grid->getHTML();

?>

</BODY>
</HTML>
