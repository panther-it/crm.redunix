<?
require_once __DIR__ . "/textbox.php";
$js_includes[] = "vatid.js"; //add
$js_includes[] = "generic.js"; //add

class VATIDField extends TextBox 
{
        function __construct($param1 = null)
        {
		$this->name   = "vatid"; //default
		$this->label  = "BTW-nr";
                parent::__construct($param1);
		$this->length = 20;
		$this->javascript->onChange = "checkVATID(this);";
        }

}


?>
