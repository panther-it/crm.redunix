<?
require_once __DIR__ . "/textbox.php";
$js_includes[] = "password.js"; //add
$js_includes[] = "generic.js"; //add

class PasswordField extends TextBox 
{
        function __construct($param1 = null)
        {
		$this->name     = "password"; //default
		$this->type     = "password"; //default
		$this->label    = "Wachtwoord";
		$this->required = true;
		$this->length   = 10;
                parent::__construct($param1);
		$this->javascript->onChange = "checkPassword(this);";
        }

}


?>
