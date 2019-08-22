<?
require_once __DIR__ . "/generic.php";

class Button extends genericField 
{
        public $type ;
        public $src  ;
        public $label; 
        public $name ; 
	public $onClick;

        function __construct($param1)
        {
                parent::__construct($param1);
		$this->name    = "submit";
		$this->type    = "submit";
		$this->label   = "submit";
		$this->onClick = "doRow(this); return false;";

                if (isset($param1))
                        if (is_string($param1)) 
			{
                                $this->label = $param1;
                                $this->name  = $param1;
			}
                        else if (is_array($param1))
			{
                                self::setAttributes($param1);
				if (isset($param1["type" ])) $this->type  = $param1["type" ] ;
				if (isset($param1["label"])) $this->label = $param1["label"];
				if (isset($param1["label"])) $this->name  = $param1["label"];
				if (isset($param1["name" ])) $this->name  = $param1["name" ];
				if (isset($param1["src"  ])) $this->src   = $param1["src"  ]  ;
			}
                        else 
                                self::setAttributes($param1);
        }


        function getHTML($row = NULL)
        {
		if ($this->readonly) $this->style .= "color: grey;"; //TODO: move this to setAttribute

                $html = "<INPUT TYPE  = \"" . $this->type    . "\""
                      . "       NAME  = \"" . $this->name    . "\""
                      . "       VALUE = \"" . $this->label   . "\""
                      . "       ALT   = \"" . $this->label   . "\""
                      . "       STYLE = \"" . $this->style   . "\""
                      . (!empty($this->src) ? "       SRC   = \"" . $this->src   . "\"" : "")
                      . (!$this->readonly   ? "" : "DISABLED = \"disabled\"")
                      . "     onClick = \"" . $this->onClick . "\""
                      . "/>\n";
                return $html;
         }
}


?>
