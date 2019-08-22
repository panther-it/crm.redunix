<? require_once(__DIR__ . "/../header.php"); ?>
-&gt; <A HREF="tasks.php" CLASS="breadcrum">tasks</A>
</SPAN>
<?
require_once __DIR__ . "/../classes/form.php";
require_once __DIR__ . "/../classes/sql/sqltasks.php";
require_once __DIR__ . "/../classes/fields/generic/textbox.php";
require_once __DIR__ . "/../classes/fields/generic/date.php";
require_once __DIR__ . "/../classes/fields/generic/linkbutton.php";
require_once __DIR__ . "/../classes/fields/contacts.php";
require_once __DIR__ . "/../classes/fields/customers.php";
require_once __DIR__ . "/../classes/fields/devices.php";
require_once __DIR__ . "/../classes/fields/generic/hidden.php";


$form= new Form("Task");
if (isset($_GET["id"]))
{
    $form->datasource        = SqlTasks::query(Settings::ASFORM, "(parent = parent OR parent is NULL) AND id = '" . $_GET["id"] . "'");
    $form->fields["id"]      = new LinkButton("task.php?id={id}");
}
$form->fields["subject"]     = new TextBox();
$form->fields["category"]    = new TextBox();
$form->fields["date_end"]    = new DateField();
$form->fields["customer"]    = new CustomersField(); //LinkButton("customer.php?id={customer}");
$form->fields["manager"]     = new ContactsField();
$form->fields["executor"]    = new ContactsField();
$form->fields["device"]      = new DevicesField();
$form->fields["description"] = new TextBox(Array("type" => "textarea", "style" => "height: 400px"));
print $form->getHTML();

if (isset($_GET["id"]))
{
	$grid = new Grid("SubTasks");
	$grid->datasource = SqlTasks::query(Settings::ASGRID,"parent = '" . addslashes($_GET["id"]) . "'"); 
	$grid->fields["id"]         = new LinkButton("task.php?id={id}");
	$grid->fields["subject"]    = new TextBox();
	$grid->fields["category"]   = new TextBox();
	$grid->fields["date_end"]   = new DateField();
	$grid->fields["manager"]    = new ContactsField();
	$grid->fields["executor"]   = new ContactsField();
	$grid->fields["device"]     = new DevicesField();
	$grid->fields["priority"]   = new TextBox();
	$grid->fields["status"]     = new TextBox();
	$grid->fields["parent"]     = new HiddenField();
	$grid->fields["customer"]   = new HiddenField();
	$grid->fields["parent"]->defaultValue   = $_GET["id"];
	$grid->fields["customer"]->defaultValue = $form->fields["customer"]->value;
	$auth->setGridFilter("tasks",$grid,"owner");
	print $grid->getHTML();
}

?>

</BODY>
</HTML>
