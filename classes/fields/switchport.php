<?
require_once __DIR__ . "/devices.php";
require_once __DIR__ . "/generic/textbox.php";
require_once __DIR__ . "/generic/generic.php";

class SwitchPortField extends GenericField 
{
	public $switch; 
	public $port;


        function __construct($param1 = null)
        {
		$this->switch      = new DevicesField($param1);
		$this->switch->datasource->constraint = "type='SWITCH'";
		$this->port        = new TextBox($param1);
		$this->port->style = "width: 40px;";
		$this->port->value = "Fa0/0";
		$this->label       = "Switch";
        }

        public function getHTML($row, $rowState = 0)
	{
		parent::getHTML($row);
		return $this->switch->getHTML($row,$rowState)
                     . " "
                     . $this->port->getHTML($row,$rowState);

	}


	public function setAttributes($attr)
	{
		parent::setAttributes($attr);
		//if (!empty($attr->name) $attr->name .= "switch";
		$this->switch->setAttributes($attr);
		$this->port->setAttributes($attr);
	}


	public function __get($varname)
	{
		switch(strtolower($varname))
		{
			case "name":
				return $this->name;
			default:
				return parent::__get($varname); 
		}
	}

	public function __set($varname,$value)
	{
		parent::__set($varname,$value);
		switch(strtolower($varname))
		{
			case "name":
				$this->name        = $value;
				$port->name        = $value . "[port]";
				$switch->name      = $value . "[switch]";
				break;
			default:
				$port->$varname    = $value;
				$switch->$varname  = $value;
		}
	}

 

}


?>
