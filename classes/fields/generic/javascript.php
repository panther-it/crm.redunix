<?
class JavascriptAttributes 
{
        public  $onChange;
        public  $onClick;
        public  $onFocus;
        public  $onBlur;
        public  $onSubmit;
	private $field;


        function __construct($param1 = NULL)
        {
                if (isset($param1))
                        if (is_object($param1) || is_array($param1))
                                self::setAttributes($param1);
        }


        public function setAttributes($attr)
        {
                if (is_array($attr))
                {
                        if (isset($attr["onChange"])) $this->onChange = $attr["onChange"];
                        if (isset($attr["onClick"])) $this->onClick = $attr["onClick"];
                        if (isset($attr["onFocus" ])) $this->onFocus  = $attr["onFocus" ];
                        if (isset($attr["onBlur"  ])) $this->onBlur   = $attr["onBlur"  ];
                        if (isset($attr["onSubmit"])) $this->onSubmit = $attr["onSubmit"];
                        if (isset($attr["field"  ])) $this->field     = $attr["field"  ];
                }
		if (is_object($attr))
			$this->field = $attr;
        }


	public function getHTML()
	{
		$html = "";

		if (isset($this->onChange) || $this->field->required) 
                                            $html .= " onChange=\"" . (!empty($this->onChange) ? str_replace("\"","\\\"",$this->onChange) : "")
                                                                   . ($this->field->required  ? " required(this);" : "")
                                                  . "\" ";
		if (isset($this->onClick )) $html .= " onClick=\""  . str_replace("\"","\\\"",$this->onClick ) . "\" ";
		if (isset($this->onFocus )) $html .= " onFocus=\""  . str_replace("\"","\\\"",$this->onFocus ) . "\" ";
		if (isset($this->onBlur  )) $html .= " onBlur=\""   . str_replace("\"","\\\"",$this->onBlur  ) . "\" ";
		if (isset($this->onSubmit)) $html .= " onSubmit=\"" . str_replace("\"","\\\"",$this->onSubmit) . "\" ";
		return $html;
	}

}

?>
