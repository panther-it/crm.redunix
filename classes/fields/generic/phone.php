<?
require_once __DIR__ . "/textbox.php";
$js_includes[] = "phone.js"; //add

class PhoneField extends TextBox 
{
        function __construct($param1 = null)
        {
		$this->name     = "phone";
		$this->label    = "Telefoon";
		$this->required = true;
		$this->length   = 7;
                parent::__construct($param1);
		$this->javascript->onChange = "checkPhone(this);";
        }

}


?>
