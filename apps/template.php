<?
require_once __DIR__ . "/../classes/fields/generic/dropdownlist.php";
require_once __DIR__ . "/../classes/db/sqlcustomers.php";
require_once __DIR__ . "/../classes/template.php";
require_once __DIR__ . "/../classes/settings.php";

$customer    = new DropDownList(SqlCustomers::query(Settings::ASLIST));
$customerid  = $_POST["customer"];
$product     = $_POST["product"];
$ipaddresses = $_POST["ipaddresses"];
$username    = $_POST["username"];
$password    = $_POST["password"];

if (isset($product))
{
        $form= new Template("colocatie.grafix");
        $form->fields["COMPANY"]     = $company;
        $form->fields["PRODUCT"]     = $product;
        $form->fields["IPADDRESSES"] = $ipaddresses;
        $form->fields["USERNAME"]    = $username;
        $form->fields["PASSWORD"]    = $password;
        $form->printHTML();
}
else
{
?>
<HTML>
<HEAD>
        <TITLE>ISP Hosting CRM</TITLE>
	<LINK REL="stylesheet" TYPE="text/css" HREF="style/general.css">
	<LINK REL="stylesheet" TYPE="text/css" HREF="style/form/red.css">
</HEAD>
<BODY>
<FORM ACTION="" METHOD="post">
<TABLE>
        <TR><TH>Klant      </TH><TD><?= $customer->getHTML() ?></TD></TR>
        <TR><TH>Product    </TH><TD><INPUT TYPE="text" NAME="product"  VALUE="<?= $product  ?>" /></TD></TR>
        <TR><TH>IP Adressen</TH><TD><TEXTAREA NAME="ipaddresses" COLS="15" ROWS="8" ><?= $ipadresses ?></TEXTAREA></TD></TR>
        <TR><TH>Username   </TH><TD><INPUT TYPE="text" NAME="username" VALUE="<?= $username ?>" /></TD></TR>
        <TR><TH>Password   </TH><TD><INPUT TYPE="text" NAME="password" VALUE="<?= $password ?>" /></TD></TR>
</TABLE>
</FORM>
</BODY>
</HTML>
<? } ?>
