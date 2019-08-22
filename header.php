<?
require_once(__DIR__ . "/classes/authorization.php");
if (!$auth->valid) $auth->redirect("/login.php");
if ($auth->toSSI)
{
print "<!--#include virtual=\"" . dirname($_SERVER["HTTP_ORIGINAL_URI"] . "dummy") . "/header.shtml\"  -->\n";
error_log(dirname($_SERVER["HTTP_ORIGINAL_URI"] . "dummy") );
error_log($_SERVER["HTTP_ORIGINAL_URI"]);
}
else //if (!$auth->viaSSI)
{
require_once(__DIR__ . "/webparts/header.php");
require_once(__DIR__ . "/webparts/framework_top.php");
}
?>
