<?
require_once __DIR__ . "/generic.php";

class HiddenField extends genericField 
{
        public $url;

        function __construct($param = NULL)
        {
                parent::__construct($param);
        }


        public function getHTML($row)
        {
		parent::getHTML($row);
		$value = $this->value;

                $html = "<INPUT TYPE  = \"hidden\""
                      . "       NAME  = \"" . $this->name . "\""
                      . "       VALUE = \"" . $value      . "\""
                      . "/>\n";
                return $html;
         }
}


?>
