<?
require_once __DIR__ . "/generic.php";

class DropDownList extends GenericField
{
	public $datasource;
        public $values;
	public $text;


        function __construct($param1 = NULL)
        {
                require_once __DIR__ . "/../../database.php";

                parent::__construct($param1);
                if (isset($param1))
                        if (is_object($param1) || is_array($param1))
                                self::setAttributes($param1);
                        else
                                $this->datasource = new $param1();
//		if (empty($this->style))
//			$this->style = "width: 100px;";
        }


        function setAttributes($attr)
        {
                parent::setAttributes($attr);
        }


        function getHTML($row = NULL, $rowState = 0)
        {
                global $database;
		session_start();
		$html          = "";
		$saveValues    = "";
		$selectedValue = "";

		parent::getHTML($row);

		if (is_object($row)) 
			$row = get_object_vars($row); //object2array
		if (!isset($row))
			$selectedValue = $this->defaultValue;
		else
			$selectedValue = $row[$this->name];
	
		if (!$this->readonly && $this->visible)
		{
                	$html = "<SELECT NAME = \"" . $this->name  . "\""
                      	      . "     onfocus = \"getFieldValues(this)\""
			      . (isset($this->javascript) ? $this->javascript->getHTML() : "")
                              . "          ID = \"##MD5KEY##\"" 
                              . "       Class = \"##MD5KEY## " . get_class($this) . "\"" 
                              . "       STYLE = \"" . $this->style . "\""
                              . ">\n"
                              . "<OPTION VALUE=\"\" />\n";
		}

                if (isset($this->datasource))
                {
                        if (is_array($this->datasource))
                                $datasource = $this->datasource[$rowState];
                        else
 				$datasource = $this->datasource;

			switch($rowState)
			{
				case Grid::INSERT_ROW: $sql = $datasource->query(Settings::ASCREATELIST, $datasource->constraint); break;
				case Grid::NORMAL_ROW: $sql = $datasource->query(Settings::ASLIST      , $datasource->constraint); break;
				default              : $sql = $datasource->query(Settings::ASLIST      , $datasource->constraint); break;
			}
			if (is_string($sql))
			{	//convert sql query to Array of values
                        	$rs   = $database->query($sql);
				$sql  = Array();
				if (is_resource($rs))
                        	while($record = mysql_fetch_array($rs))
					array_push($sql,$record);
			}
                        foreach ($sql as $r)
			{
				if ($selectedValue == $r[0])
				{
				    if ($this->readonly || !$this->visible)
				        $html .= "<INPUT TYPE=\"hidden\" 
                                                         NAME=\"" . $this->name . "\" 
                                                        VALUE=\"" . $r[0]       . "\" />";
				    else
                                        $html .= "<OPTION SELECTED value=\"" . $r[0] . "\">" . $r[1] . "</OPTION>\n";        
				    $this->text = htmlentities($r[1]);
                                    $html2 = "<SPAN CLASS=\"text\">" .  htmlentities($r[1]) . "</SPAN>";
				}
				//if (!$this->readonly)
				$saveValues .= $r[0] . "===" . $r[1] . "\n";
			}
                }
		if (!$this->readonly && $this->visible)
                    $html .= "</SELECT>\n";
		if ($this->visible)
		    $html .= $html2;

		//remember values for dynamic/ajax loading of field
		$md5key  = md5($saveValues);
		$md5key2 = $md5key . md5($selectedValue);
		$_SESSION[$md5key] = $saveValues;
		$html = str_replace("##MD5KEY##",$md5key2,$html);

                return $html;
         }


        static function getValues($md5key)
        {
		session_start();
		//md5key = [values][selectedValue], each 32-bits md5
		$md5key = substr($md5key,0,32);
		$values = $_SESSION[$md5key];
		//error_log("dropDownList: md5key = " . $md5key . "; values = " . $values . ";");
		return $values;
         }

	static function updateValues($md5key, $query)
	{
		global $database;
		session_start();
		$md5key = substr($md5key,0,32);
		$_SESSION[$md5key] = "";
		$values = $database->getList($query);
		$_SESSION[$md5key] = $values;
		return $values;
	}
}


?>
