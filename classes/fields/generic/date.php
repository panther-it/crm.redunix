<?
require_once __DIR__ . "/textbox.php";

class DateField extends TextBox 
{
        public $length;
	public $type; // text | textarea | password


        function __construct($param1 = null)
        {
		$this->length     = 10;
		$this->label      = "Datum";
                parent::__construct($param1);
                if (isset($param1))
                	self::setAttributes($param1);
        }

}


?>
