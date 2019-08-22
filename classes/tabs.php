<?
class Tabs 
{
        const INCLUDE_FILES = "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"/lib/tabs.js\"    TYPE=\"text/javascript\"></SCRIPT> 
                               <LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"/style/tabs/red.css\">";

	private $content = array();

        function __construct() 
        {
        }

	public function __get($varname)
	{
		return $this->content[$varname]; 
	}

	public function __set($varname,$value)
	{
		$this->content[$varname] = $value;
	}


	public function getHeaderHTML() 
	{
                $html = self::INCLUDE_FILES . "\n"
		      . "<DIV ID=\"tab_header\">\n";
		foreach ($this->content as $tabName => $tabContent)
			$html .= "<A HREF=\"\" onClick=\"showTab('tab_" . $tabName . "'); return false;\">"
                              .  str_replace("_"," ",$tabName)
                              .  "</A>\n";
		$html .= "</DIV>\n\n";
		return $html;
	}


	public function getHTML() 
	{
		$html = $this->getHeaderHTML();
		foreach ($this->content as $tabName => $tabContent)
			$html .= "<DIV ID=\"tab_" . $tabName . "\" CLASS=\"tab\">\n"
                              .  $tabContent
                              .  "</DIV>\n";
		return $html;
	}


}

$tabs = new Tabs();
?>
