<?
require_once __DIR__ . "/javascript.php";

class GenericField 
{
        public $name;
        public $value;
        public $style;
        public $cellStyle;
        public $readonly     = false;
        public $visible      = true;
	public $required     = false;
        public $defaultValue;
	public $javascript;
	private $pLabel;
	public $parent; //used by LinkDropDown


        function __construct($param1 = NULL)
        {
		$readonly = false;
		$visible  = true;
		$this->javascript = new JavascriptAttributes($this);

                if (isset($param1))
			if (is_string($param1))
				$this->name = $param1;
                        else if (is_object($param1) || is_array($param1))
                                self::setAttributes($param1);
        }


	public function __toString()
	{
		$className = get_called_class();
		return $className . "[" . $this->name . "]"; 
	}


	public static function getInstance($param1 = NULL)
	{
		$className = get_called_class();
		return new $className($param1);
	}


	public function getHTML($row = NULL)
	{
		$this->value = $this->defaultValue;
                if (is_array($row)  && array_key_exists($this->name,$row)) $this->value = $row[$this->name];
		if (is_object($row) && property_exists($row,$this->name) ) $this->value = $row->{$this->name};
	}

        function setAttributes($attr)
        {
                if (is_object($attr))
                {
                        if (isset($attr->name        )) $this->name         = $attr->name;
                        if (isset($attr->def         )) $this->defaultValue = $attr->def;
                        if (!empty($attr->not_null   )) $this->required     = true;
                        if (!empty($attr->primary_key)) $this->required     = true;
                        if (isset($attr->required    )) $this->required     = $attr->required;
                        if (isset($attr->style       )) $this->style        = $attr->style;
                        if (isset($attr->label       )) $this->label        = $attr->label;
                        if (isset($attr->readonly    )) $this->readonly     = $attr->readonly;
                }
                else if (is_array($attr))
                {
                        if (isset($attr["name"]        )) $this->name         = $attr["name"];
                        if (isset($attr["def"]         )) $this->defaultValue = $attr["def"];
                        if (!empty($attr["not_null"]   )) $this->required     = true; 
                        if (!empty($attr["primary_key"])) $this->required     = true; 
                        if (isset($attr["required"]    )) $this->required     = $attr["required"];
                        if (isset($attr["style"]       )) $this->style        = $attr["style"];
                        if (isset($attr["label"]       )) $this->label        = $attr["label"];
                        if (isset($attr["readonly"]    )) $this->readonly     = $attr["readonly"];
                }
        }

	public function __get($varname)
	{
		switch(strtolower($varname))
		{
			case "label":
				return empty($this->pLabel) ? $this->name : $this->pLabel; 
			default:
		}
	}

	public function __set($varname,$value)
	{
		switch(strtolower($varname))
		{
			case "label":
				$this->pLabel = $value;
				break;
			default:
		}
	}


}

?>
