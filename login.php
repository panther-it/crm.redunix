<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
        <TITLE>ISP Hosting CRM</TITLE>
	<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
	<LINK REL="stylesheet" TYPE="text/css" HREF="/style/generic.css" />
	<LINK REL="stylesheet" TYPE="text/css" HREF="/style/redunix.css" />
	<LINK REL="stylesheet" TYPE="text/css" HREF="/style/form/red.css" />
	<SCRIPT>
		//if (window != top) top.location.href = window.location.href;

		function setFocus()
		{
			var usrField = document.forms[0].elements['username'];
			usrField.select();
		}
		window.onload = setFocus;
	</SCRIPT>
</head>
<BODY>
<CENTER>
<BR/>
<BR/>
<BR/>
<BR/>
<BR/>
<?
require_once __DIR__ . "/classes/authorization.php";
require_once __DIR__ . "/classes/form.php";
require_once __DIR__ . "/classes/sql/sqlcustomers.php";

$form                     = new Form("Login");
$form->datasource         = array("username" => $auth->username
//                               ,"password" => $auth->password
                                 );
$form->fields["username"] = new TextBox();
$form->fields["password"] = new TextBox(array("type" => "password"));
$form->submitButton->label   = "Login";
$form->submitButton->onClick = "";
$form->action="login.php";

if (isset($_GET["logout"])) $auth->logout();
if ($auth->valid) $auth->redirect();

print $form->getHTML();

?>
</CENTER>
</BODY>
</HTML>
