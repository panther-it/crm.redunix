<?
require_once __DIR__ . "/generic.php";

class BooleanField extends GenericField
{
        function __construct($param1 = null)
        {
                parent::__construct($param1);
                if (isset($param1))
                	self::setAttributes($param1);
        }


        function setAttributes($attr)
        {
                parent::setAttributes($attr);
        }


        function getHTML($row)
        {
		parent::getHTML($row);
		$value = $this->value;
                
                	$html = "<INPUT TYPE  = \"checkbox\""
                      	      . "       NAME  = \"" . $this->name  . "\""
                              . "       VALUE = \"1\""
                     .($value ? "       CHECKED " : "")
                              . "       STYLE = \"" . $this->style . "\""
                              . "/>"
                              . "<SPAN CLASS=\"text\"><IMG STYLE=\"width: 16px; height: 16px;\"  SRC=\"/style/grid/img/" . ($value == "1" ? "save.png" : "delete.jpg") . "\" /></SPAN>";
                return $html;
         }
}


?>
