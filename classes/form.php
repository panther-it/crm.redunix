<?
require_once __DIR__ . "/database.php";

class Form 
{
        const INCLUDE_FILES = "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/form.js\"    TYPE=\"text/javascript\"></SCRIPT> 
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/grid.js\"    TYPE=\"text/javascript\"></SCRIPT> 
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/ajax.js\"    TYPE=\"text/javascript\"></SCRIPT> 
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/generic.js\" TYPE=\"text/javascript\"></SCRIPT>
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/msg.js\"     TYPE=\"text/javascript\"></SCRIPT>
                               <SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/dropDownList.js\" TYPE=\"text/javascript\"></SCRIPT>
                               <LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"/style/form/red.css\">";

	public $action;
        public $name;
        public $datasource;
        public $fields = Array();
        public $submitButton;


        function __construct($name)
        {
                session_start();
                require_once "fields/generic/textbox.php";
                require_once "fields/generic/linkbutton.php";
                require_once "fields/generic/dropdownlist.php";
                require_once "fields/generic/button.php";
                require_once "fields/generic/boolean.php";
                require_once "fields/generic/hidden.php";
                $this->name         = $name;
		$this->action       = "javascript:doRow(this); return false;";
		$this->submitButton = new Button("update");
        }


        /*
         * Name       : getHTML()
         * Description: builds the HTML Table/Form
         */
        public function getHTML()
        {
                global $database;
                $html = self::INCLUDE_FILES . "\n"
                      . "<H1>" . $this->name . "</H1>\n";

		if (empty($this->datasource))
		{
			if ($this->submitButton->label == "update") $this->submitButton->label = "insert";
			$this->submitButton->name  = "insert";
			$qtyforms = 1;
                        foreach (array_keys($this->fields) as $fieldname)
                                self::initField($this->fields[$fieldname],array(name => $fieldname));
		}
		if (is_string($this->datasource))
		{
			$forms = $database->query($this->datasource);
                	if (is_string($forms))
			{
                        	print "<TABLE class=\"form\"><TR><TD>" . $forms . "</TD></TR></TABLE>\n"; //$rs = error message
				flush();
				return;
			}
			$qtyforms  = mysql_num_rows($forms);
			while ($field = mysql_fetch_field($forms))
                                self::initField($field);
                        $values    = mysql_fetch_object($forms);
		}
		if (is_resource($this->datasource))
		{
			$forms     = $this->datasource;
			$qtyforms  = mysql_num_rows($forms);
			while ($field = mysql_fetch_field($forms))
                                self::initField($field);
                        $values    = mysql_fetch_object($forms);
		}
		if (is_object($this->datasource))
		{
			$values    = $this->datasource;
			$qtyforms  = 1;
                        foreach (array_keys($this->fields) as $fieldname)
                                self::initField($this->fields[$fieldname],array(name => $fieldname));
		}
		if (is_array($this->datasource))
		{
			$values    = $this->datasource;
			$qtyforms  = 1;
                        foreach (array_keys($this->fields) as $fieldname)
                                self::initField($this->fields[$fieldname],array(name => $fieldname));
		}

                for ($formnr = 0; $formnr < $qtyforms; $formnr++)
                {
                        if ($qtyforms > 1) $values = mysql_fetch_object($forms);
			$action = str_replace("javascript:","\" onSubmit=\"",$this->action);
                        $html .= "<FORM NAME=\"" . $this->name . "\" ACTION=\"" . $action . "\" METHOD=\"post\">\n"
                              .  "<TABLE class=\"form\">\n"
                              .  "<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"" . $this->submitButton->name . "\" />\n"
                              .  "<INPUT TYPE=\"hidden\" NAME=\"class\"  VALUE=\"" . $this->name . "\" />\n";

                        foreach ($this->fields as $field)
                        {
			    if ((get_class($field) != "HiddenField") && $field->visible)
                                $html .= "<TR>\n"
                                      .  self::getHeaderCell($field)
                                      .  self::getNormalCell($field, $values)
                                      .  "</TR>\n";
			    else
				$html .= $field->getHTML($values);
                        }

                        $html .= "<TR><TH></TH><TD colspan=\"100\">"
                              .  $this->submitButton->getHTML()
		              .  "</TD></TR></TABLE></FORM>\n";
                }

                return $html;
        }


        /* 
         * Name       : initField
         * Description: Set field properties based on DB metadata. Create field first if not exists
         */
        private   function initField($field, $attributes = NULL)
        {
		if (is_array($attributes))
		{
			$field->setAttributes($attributes);
		}
                if (!isset($this->fields[$field->name]))
                        switch($field->type)
                        {
                                case "string":
                                        //$this->fields[$field->name] = new TextBox($field);
                                default:
                                        //$this->fields[$field->name] = new TextBox($field);
                        }
                else
                        $this->fields[$field->name]->setAttributes($field);
         }


        protected function getHeaderCell($field)
        {
                $html  = "<TH>" . $field->label. "</TH>\n";
                return $html;
        }


        protected function getNormalCell($field, $values)
        {
              	  $html = "<TD style=\"" . $field->cellStyle . "\">"
                        . $field->getHTML($values)
                        . "</TD>\n";
                 return $html;
        }

        
        protected function getHeaderButtons()
        {
                $html  = "<A HREF    = \"\""
                       . "   onClick = \"toggleDisplayFilterRow(this); return false\""
                       . ">F</A>\n";
                $html .= "<A HREF    = \"\""
                       . "   onClick = \"toggleDisplayInsertRow(this); return false\""
                       . ">I</A>\n";
                return $html;
        }

}

?>
