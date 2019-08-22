<?
include('client_class.php');

$cmd['command'] = "createvisit";
$cmd['contactid'] = "123";
$cmd['cardid'] = "1017";
$cmd['date'] = "14062006";
$cmd['time'] = "1300";
$cmd['worktime'] = "120";
$cmd['nocid'] = "1";
$cmd['note'] = "Harddisk replacement server 212";

$grafix = new NOC_interface();
$grafix->AddParam($cmd);                
$grafix->DoTransaction();              

echo "<pre>";
echo "RETcode: ".$grafix->values['RETcode']."<br />";
echo "RETtext: ".$grafix->values['RETtext']."<br />";
echo "NOCcode: ".$grafix->values['NOCcode']."<br />";
echo "is_success: ".$grafix->values['is_success']."<br />";
echo "</pre>";
?>