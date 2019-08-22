<?
require_once __DIR__ . "/textbox.php";
$js_includes[] = "zipcode.js"; //add

class ZipcodeField extends TextBox 
{
        function __construct($param1 = null)
        {
		$this->name     = "zipcode"; //default
		$this->label    = "Postcode";
		$this->required = true;
		$this->length   = 7;
                parent::__construct($param1);
		$this->javascript->onChange = "checkZipcode(this);";
        }

}


?>
