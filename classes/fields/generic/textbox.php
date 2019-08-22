<?
require_once __DIR__ . "/generic.php";

class TextBox extends GenericField
{
        public $length;
	public $type; // text | textarea | password


        function __construct($param1 = null)
        {
		if (empty($this->type)) $this->type = "textbox"; //default
                parent::__construct($param1);
                if (isset($param1))
                	self::setAttributes($param1);
        }


        function setAttributes($attr)
        {
                parent::setAttributes($attr);
                if (is_object($attr))
                {
                        if (isset($attr->length   )) $this->length   = $attr->length;
                        if (isset($attr->readonly )) $this->readonly = $attr->readonly;
                }
                else if (is_array($attr))
                {
                        if (isset($attr["length"]   )) $this->length   = $attr["length"];
                        if (isset($attr["readonly"] )) $this->readonly = $attr["readonly"];
                        if (isset($attr["type"]     )) $this->type     = $attr["type"];
                }
        }


        function getHTML($row="")
        {
		parent::getHTML($row);
		$value = $this->value;
		if ($this->type == "password") $value = "";
                
		if ($this->readonly)
                	$html = "<INPUT TYPE  = \"hidden\""
                      	      . "       NAME  = \"" . $this->name  . "\""
                              . "       VALUE = \"" . $value       . "\""
                              . "       SIZE  = \"1\" "
                              . "/>" . $value;
		else
                	$html =($this->type == "textarea" 
                              ? "<TEXTAREA        " 
                      	      . "       NAME  = \"" . $this->name  . "\""
                              . "       STYLE = \"" . $this->style . "\""
			      . (isset($this->javascript) ? $this->javascript->getHTML() : "")
                              . ">" . $value . "</TEXTAREA>"
                              : "<INPUT TYPE  = \"" . $this->type  . "\""
                      	      . "       NAME  = \"" . $this->name  . "\""
                              . "       VALUE = \"" . $value       . "\""
			      . (isset($this->javascript) ? $this->javascript->getHTML() : "")
                              . "       STYLE = \"" . $this->style . "\""
                              . "/>"
                               )
                              . "<SPAN CLASS=\"text\">" . htmlentities($value) . "</SPAN>";
                return $html;
         }
}


?>
