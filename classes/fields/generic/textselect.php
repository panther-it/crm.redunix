<?
require_once __DIR__ . "/generic.php";

class TextSelect extends GenericField
{
        public $listvalues;


        function __construct($param1)
        {
                parent::__construct($param1);
                if (isset($param1))
                        if (is_object($param1))
                                self::setAttributes($param1);
        }


        /*
         * Sets the properties of this Class (Sets the attributes of this element)
         */
        function setAttributes($attr)
        {
                parent::setAttributes($attr);
                if (is_object($attr))
                {
                        if (isset($attr->listvalues )) $this->listvalues = $attr->listvalues;
                }
                else if (is_array($attr))
                {
                        if (isset($attr->listvalues )) $this->listvalues = $attr->listvalues;
                }
        }


        function getListValuesAsJS($rowState = 0)
        {
               $jslist   = "listvalues[\"" . $this->name . "\"]";
               $jsvalues = "";
               $i        = 0;
               $listvalues = $this->listvalues;
          
               if (is_array($listvalues)) 
                        $listvalues = $listvalues[$rowState];

               if (strtoupper(substr(trim($listValues,0,6))) == "SELECT")
               {
                        $listvalues = $database->query($listvalues);
               }

               if (is_resource($listvalues)) //mysql recordset
               {
                        while ($row = mysql_fetch_array($listvalues)) 
                        {
                                $jsvalues .= $jslist . "[" . $i   . "][0] = '" . mysql_escape_string($row[0]) . "';\n";
                                $jsvalues .= $jslist . "[" . $i++ . "][1] = '" . mysql_escape_string($row[1]) . "';\n";
                        }
               }
               else if (is_array($listvalues))
               {
                        foreach ($listvalues as $id => $label)
                        {
                                $jsvalues .= $jslist . "[" . $i   . "][0] = '" . mysql_escape_string($id   ) . "';\n";
                                $jsvalues .= $jslist . "[" . $i++ . "][1] = '" . mysql_escape_string($label) . "';\n";
                        }
               }

               return $jsvalues;
        }


        function getHTML($row, $rowState = 0)
        {
		parent::getHTML($row);
		$value = $this->value;

                $html = "<SCRIPT LANGUAGE=\"javascript\">\n"
                      . $this->getListValuesAsJS($rowState)
                      . "</SCRIPT>\n"
                      . "<INPUT TYPE       = \"hidden\"" 
                      . "       NAME       = \"" . $this->name         . "\""
                      . "       VALUE      = \"" . $row->{$this->name} . "\""
                      . "/>";
		if ($this->readonly)
			$html .= $row->{$this->name};
		else
			$html .= "<INPUT TYPE       = \"text\" "
                              . "       NAME       = \"" . $this->name         . "\""
                              . "       VALUE      = \"" . $row->{$this->name} . "\""
                              . "       STYLE      = \"" . $this->style        . "\""
                              . "       onKeyPress = \"textSelectPressed(this)\" "
                              . ($readonly ? " READONLY " : "")
                              . "/>"
                              . "<UL    NAME       = \"" . $this->name         . "\""
                              . "></UL>";

                return $html;
         }
}


?>
