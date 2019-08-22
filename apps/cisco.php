<?
require_once(__DIR__ . "/../header.php");
require_once(__DIR__ . "/../classes/telnet.php");

$telnet  = $_SESSION["cisco_telnet"];
$buffer = $_SESSION["cisco_buffer"];
if (!isset($cisco) || isset($_POST["reset"]))
{
	$telnet = new Telnet("sw-t2-2.network.redunix.net");
	$buffer = ""; 
	$_SESSION["cisco_telnet"] = $telnet;
	$_SESSION["cisco_buffer"] = $buffer;
}

if (isset($_POST["command"])) 
    $buffer .= $telnet->cmd($_POST["command"]);
else
    $buffer .= $telnet->read();
?>

<form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" onload="this.elements[0].focus()">
<table border="1" style="width: 100%; height: 90%;">
    <tr style="height: 90%;" valign="top">
        <td><?= nl2br(htmlspecialchars($buffer)) ?></td>
    </tr>
    <tr>
        <td>
            <input type="text"   name="command" value=""     style="width: 100%;" />
            <input type="submit" name="submit"  value="send" />
            Reset <input type="checkbox" name="reset"  value="reset" />
    </tr>
</table>



</BODY>
</HTML>
