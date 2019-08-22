<?
require_once __DIR__ . "/database.php";
require_once __DIR__ . "/fields/all.php";
require_once __DIR__ . "/fields/generic/all.php";

class Grid
{
        const HEADER_ROW    = -3;
        const FILTER_ROW    = -2;
        const INSERT_ROW    = -1;
        const NORMAL_ROW    =  0;
        const INCLUDE_FILES = "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/grid.js\"         TYPE=\"text/javascript\"></SCRIPT> 
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/generic.js\"      TYPE=\"text/javascript\"></SCRIPT>
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/msg.js\"          TYPE=\"text/javascript\"></SCRIPT>
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/ajax.js\"         TYPE=\"text/javascript\"></SCRIPT>
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/dropDownList.js\" TYPE=\"text/javascript\"></SCRIPT>
                               <LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"/style/grid/red.css\">";

	public $formUrl;
        public $name;
        public $datasource;
        public $filter     = "";
	public $readonly   = false;
        public $showFilter = true;
        public $showButtons= true;
        public $showInsert = true;
        public $fields     = Array();
	public $buttons    = Array();


        function __construct($name)
        {
                session_start();
                $this->name = $name;
		$this->formUrl = "/tables/" . preg_replace("/s$/","",strtolower($name)) . ".php";
                $this->initFilter();
		$this->buttons[0] = new Button(Array( "name"  => "update"
                                                    , "label" => "update"
                                                    , "type"  => "image"
                                                    , "src"   => "/style/grid/img/save.png"
                                                    , "style" => "width: 16px; height: 16px;"));
		$this->buttons[1] = new Button(Array( "name"  => "delete"
                                                    , "label" => "delete"
                                                    , "type"  => "image"
                                                    , "src"   => "/style/grid/img/delete.jpg"
                                                    , "style" => "width: 16px; height: 16px;"));
        }

        private function initFilter()
        {
                if (isset($_GET[$this->name]["filter"]))
                {
                        $this->filter = $_GET[$this->name][filter];
                        $_SESSION[$this->name]["filter"] = $this->filter; //save filter
                }
                else
                        $this->filter = $_SESSION[$this->name]["filter"]; //load filter
        }


        /*
         * Name       : getHTML()
         * Description: builds the HTML Table/Grid
         */
        public function getHTML()
        {
                global $database;

                $html = self::INCLUDE_FILES . "\n"
                      . "<H1>" . ucfirst($this->name) . "</H1>" . ($this->readonly ? "" : " [<a href=\"" . $this->formUrl . "\">Add</a>]\n")
                      . "<TABLE class=\"grid " . ($this->readonly ? "readonly" : "") . "\">\n";
                $rs   = $database->query($this->datasource); //, $this->filter);
		if (mysql_num_rows($rs) == 0 && $this->readonly) return; //show nothing
                if (is_string($rs))
                        $html .= "<TR><TD>" . $rs . "</TD></TR>\n"; //$rs = error message
                else

                for ($rownr = self::HEADER_ROW; $rownr < mysql_num_rows($rs); $rownr++)
                {
                        switch($rownr)
                        {
                                case self::HEADER_ROW;
                                        $html .= "<TR class=\"header\">\n"
					      .  "<FORM ACTION=\"\" METHOD=\"get\">\n"
                                              .  "<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"sort\" />\n"
                                              .  "<TH>" . self::getHeaderRowButtons() . "</TH>\n";
                                        break;
                                case self::FILTER_ROW:
                                        $html .= "<TR class=\"filter\" STYLE=\"" . ($this->showFilter ? "display: table-row;" : "")  . "\">\n"
					      .  "<FORM ACTION=\"\" METHOD=\"get\">\n"
                                              .  "<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"filter\" />\n"
                                              .  "<TD>" . self::getFilterRowButtons() . "</TD>\n";
                                        break;
                                case self::INSERT_ROW;
                                        $htmlInsert .= "<TR class=\"insert\"  STYLE=\"" . ($this->showInsert && !$this->readonly ? "display: table-row;" : "")  . "\">\n"
					      .  "<FORM NAME=\"" . $this->name . "\" ACTION=\"\" METHOD=\"post\" onSubmit=\"doRow(this); return false;\">\n"
                                              .  "<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"insert\" />\n"
                                              .  "<INPUT TYPE=\"hidden\" NAME=\"class\"  VALUE=\"" . $this->name . "\" />\n"
                                              .  "<TD>" . self::getInsertRowButtons() . "</TD>\n";
                                        break;
                                default:
                                        $row = mysql_fetch_object($rs);
                                        if (!self::checkFilter($row)) continue 2; //skip record if not in filter
                                        $html .= "<TR class=\"normal " . ($rownr % 2 == 0 ? "even" : "odd") . "\" onClick=\"toggleSelectRow(this,'selected');\">\n"
					      .  "<FORM ACTION=\"\" METHOD=\"post\" onSubmit=\"doRow(this); return false;\">\n"
                                              .  "<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"update\" />\n"
                                              .  "<INPUT TYPE=\"hidden\" NAME=\"class\"  VALUE=\"" . $this->name . "\" />\n"
                                              .  "<TD NOWRAP><SPAN class=\"rowButtons\" STYLE=\"" . ($this->showButtons === Settings::ALWAYS ? "display:inline-block" : "") . "\">" . self::getNormalRowButtons() . "</SPAN>"
                                              .  "    <SPAN class=\"rowNr\" STYLE=\"" . ($this->showButtons === Settings::ALWAYS ? "display:none" : "") . "\">"      . $rownr                      . "</SPAN></TD>\n";
                        }

                        //for ($fieldnr = 0; $fieldnr < mysql_num_fields($rs); $fieldnr++)
			foreach($this->fields as $name => $field)
                        {
                                //$field = mysql_fetch_field($rs, $fieldnr);
                                switch($rownr)
                                {
                                        case self::HEADER_ROW:
                                                self::initField($name,$field);
						if ((get_class($field) != "HiddenField") && $field->visible)
                                                	$html .= self::getHeaderRow($field);
                                                break;
                                        case self::FILTER_ROW:
						if ((get_class($field) != "HiddenField") && $field->visible)
                                                	$html .= self::getFilterRow($field, $row);
                                                break;
                                        case self::INSERT_ROW:
                                                $row   = (object)Array($field->name => $field->defaultValue);
						//if (get_class($field) != "HiddenField")
                                                	$htmlInsert .= self::getInsertRow($field, $row);
                                                break;
                                        default:
                                              	$html .= self::getNormalRow($field, $row);
                                                break;
                                }
                        }

			switch($rownr)
			{
				case self::INSERT_ROW:
                         		$htmlInsert .= "</FORM></TR>\n";
					break;
				default:
					$html       .= "</FORM></TR>\n";
			}
                }

                $html .= $htmlInsert . "</TABLE>\n";
                return $html;
        }


        /*
         * Description: checks if the current row is captured within the filter or not
         *              returns true if in filter / filter matches
         */
        protected function checkFilter($row)
        {
                if (!empty($this->filter))
                        foreach($this->filter as $name => $value)
                                if (!empty($value)) 
                                {
					if (!array_key_exists($name,$this->fields)) continue; //skip filter if filter != field 
					if (!empty($this->fields[$name]->datasource))  
					{
						$values = $this->fields[$name]->datasource->find($value, Settings::ASIDS);
						if (!is_array($values)) print($values);
						else
						if (!in_array($row->$name,$values)) return false;
					}
					else
						if (is_numeric($value)) 
						{	if ($value != $row->$name) return false; } //numeric must match exactly
						else
                                        		if (preg_match("/" . $value . "/i",$row->$name) == 0) return false; 
                                }
		return true;
        }


        /* 
         * Name       : initField
         * Description: Set field properties based on DB metadata. Create field first if not exists
         */
        private   function initField($name, $field)
        {
		$field->name = $name;
                if (!isset($this->fields[$field->name]))
                { 
                        switch($field->type)
                        {
                                case "string":
                                //        $this->fields[$field->name] = new TextBox($field);
                                default:
                                //        $this->fields[$field->name] = new TextBox($field);
                        }
                }
                else
		{
			if (is_callable(array($this->fields[$field->name],"setAttributes")))
                        $this->fields[$field->name]->setAttributes($field);
			else
			echo $field->name . " = WIERD";
		}
         }


        protected function getHeaderRow($field)
        {
                $html = "<TH>" . $field->label . "</TH>\n";
                return $html;
        }


        protected function getFilterRow($field)
        {
                $html = "<TD>"
                      . "<INPUT TYPE  = \"text\""
                      . "       NAME  = \"" . $this->name . "[filter][" . $field->name . "]\""
                      . "       VALUE = \"" . $this->filter[$field->name] . "\""
                      . "       SIZE  = \"1\" "
                      . "/>"
                      . "</TD>\n";
                return $html;
        }

        
        protected function getInsertRow($field, $row)
        {
		 $oldState =  $field->readonly;
		 $field->readonly = false;
		 if (get_class($field) == "HiddenField" || !$field->visible)
			$html = $field->getHTML($row, self::INSERT_ROW);
		 else	
                 	$html = "<TD>"
                       	      . $field->getHTML($row, self::INSERT_ROW)
                              . "</TD>\n";
		 $field-> readonly = $oldState;
                 return $html;
        }

        protected function getNormalRow($field, $row)
        {
		 $oldState = $field->readonly;
		 if ($this->readonly) $field->readonly = $this->readonly;
		 if ((get_class($field) == "HiddenField") || !$field->visible)
			$html = $field->getHTML($row);
		 else	
                 	$html = "<TD style=\"" . $field->cellStyle . "\">"
                       	      . $field->getHTML($row)
                              . "</TD>\n";
		 $field->readonly = $oldState;
                 return $html;
        }

        
        protected function getHeaderRowButtons()
        {
	/*
                $html  = "<A HREF    = \"\""
                       . "   onClick = \"toggleDisplayFilterRow(this); return false\""
                       . "><IMG SRC=\"/style/grid/img/filter.gif\" STYLE=\"height: 16px; width: 16px;\" /></A>\n";
		if (!$this->readonly)
                $html .= "<A HREF    = \"javascript:alert('help')\""
                       . "   onClick = \"toggleDisplayInsertRow(this); return false\""
                       . "><IMG SRC=\"/style/grid/img/insert.png\" STYLE=\"height: 16px; width: 16px;\" /></A>\n";
	*/
		$html = "";
                return $html;
        }


        protected function getFilterRowButtons()
        {
                //$html  = "<A HREF    = \"\""
                //       . "   onClick = \"doFilter(this); return false\""
                //       . ">S</A>\n";
                $html  = "<INPUT TYPE=\"image\" SRC=\"/style/grid/img/filter.gif\" STYLE=\"height: 16px; width: 16px;\" ALT=\"filter\"/>";
                return $html;
        }


        protected function getNormalRowButtons()
        {
		$html = "";
		foreach($this->buttons as $button)
		{
 			if ($this->showButtons === Settings::ALWAYS) $button->style .= "display:inline-block;"; 
			$html .= $button->getHTML();
		}
                return $html;
        }


        protected function getInsertRowButtons()
        {
                $html  = "<INPUT TYPE=\"image\""
                       . "   src     = \"/style/grid/img/insert.png\""
                       . "   style   = \"width:16px; height: 16px;\""
                       . "   alt     = \"insert\""
                       . "   name    = \"insert\""
                       . "   value   = \"insert\""
                       . "/>\n";
                       //. "   onClick = \"return false;\""
                return $html;
        }
}

?>
