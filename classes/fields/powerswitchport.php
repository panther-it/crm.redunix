<?
require_once __DIR__ . "/switchport.php";

class PowerswitchPortField extends SwitchPortField 
{
	public $switch; 
	public $port;


        function __construct($param1 = null)
        {
		parent::__construct($param1);
		$this->switch->datasource->constraint = "type='POWERSWITCH'";
		$this->label = "Powerswitch";
		$this->port->value = "0";
        }

}


?>
