<?
require_once __DIR__ . "/textbox.php";
$js_includes[] = "username.js"; //add
$js_includes[] = "generic.js"; //add

class UsernameField extends TextBox 
{
        function __construct($param1 = null)
        {
		$this->name     = "username"; //default
		$this->label    = "Gebruikersnaam";
		$this->required = true;
		$this->length   = 10;
                parent::__construct($param1);
		$this->javascript->onChange = "checkUsername(this);";
        }

}


?>
