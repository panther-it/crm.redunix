<?
require_once __DIR__ . "/textbox.php";
$js_includes[] = "email.js"; //add
$js_includes[] = "generic.js"; //add

class EmailField extends TextBox 
{
        function __construct($param1 = null)
        {
		$this->name     = "email"; //default
		$this->label    = "Email";
		$this->required = true;
		$this->length   = 7;
                parent::__construct($param1);
		$this->javascript->onChange = "checkEmail(this);";
        }

}


?>
