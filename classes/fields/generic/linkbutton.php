<?
require_once __DIR__ . "/generic.php";

class LinkButton extends genericField 
{
        public $url;
        public $target;

        function __construct($param1)
        {
                parent::__construct($param1);
                self::setAttributes($param1);
        }


        public function setAttributes($attr)
        {
                parent::setAttributes($attr);
                if (is_string($attr)) 
                        $this->url = $attr;
                else if (is_object($attr))
                {
                        if (!empty($attr->url  )) $this->url   = $attr->url;
                }
                else if (is_array($attr))
                {
                        if (!empty($attr["url"]  )) $this->url   = $attr["url"];
                }
         }

	private static function replaceDBValues($string, $row)
	{
		$vars = Array();
		if (is_resource($row) || is_object($row)) $vars = get_object_vars($row);
		if (is_array($row))                       $vars = $row;
		foreach ($vars as $name => $value) 
		{
			$string = str_replace("{" . $name . "}",$value,$string);
		}
		$string = preg_replace("/{[^}]*}/","",$string); //replace non matched fields with blanks
		return $string;
	}

        public function getHTML($row)
        {
		$html  = "";
		parent::getHTML($row);
		$value = $this->value;
 		if ((strlen($this->label)==0) || ($this->label == $this->name)) $this->label = "{" . $this->name . "}";

		if (empty($this->parent)) //myself
		{
		if ($this->readonly)
                	$html = "<INPUT TYPE  = \"hidden\""
                      	      . "       NAME  = \"" . $this->name  . "\""
                              . "       VALUE = \"" . $value       . "\""
                              . "/>";
		else
                	$html = "<INPUT TYPE  = \"" . $this->type  . "\""
                      	      . "       NAME  = \"" . $this->name  . "\""
                              . "       VALUE = \"" . $value       . "\""
                              . "       STYLE = \"" . $this->style . "\""
                              . "       SIZE  = \"1\" "
                              . "/>";
		}
                $html        .= "<SPAN  CLASS = \"text\""
                              . "       STYLE = \"" . $this->style . "\">"
			      . "<A     HREF  = \"" . self::replaceDBValues($this->url, $row) . "\""
      			      . "       TARGET= \"" . $this->target . "\" "
			      . (isset($this->javascript) ? $this->javascript->getHTML() : "")
                              . ">" . htmlentities(self::replaceDBValues($this->label, $row)) . "</A>"
                              . "</SPAN>";
                 return $html;
         }
}


?>
