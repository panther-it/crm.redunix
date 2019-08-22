<?
require_once __DIR__ . "/generic.php";

class CablesGrid extends GenericGrid
{
        function __construct($constraint = NULL)
        {
		parent::__construct("Cables");
		if (!empty($constraint))
		{
			$this->datasource     = SqlCables::queryConnected($constraint);
        		$this->fields["deviceB_id"]    = new LinkButton(array( "url"   => "/device.php?id={deviceB_id}"
                        		                                     , "label" => "{deviceB_label}"));
		}
		else
		{
			$this->fields["deviceA_id"]   = new DevicesField(); 
			$this->fields["deviceA_port"] = new TextBox(); 
			$this->fields["deviceB_id"]   = new DevicesField(); 
		}
		$this->fields["deviceB_port"] = new TextBox(); 
		$this->fields["cableType"]    = new CableTypesField(); 
        }
}
