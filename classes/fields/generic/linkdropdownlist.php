<?
require_once __DIR__ . "/dropdownlist.php";

class LinkDropDownList extends DropDownList 
{
	public $viewField;

        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->viewField             = new LinkButton("/tables/unknown.php?id={customer}");
		$this->viewField->name       = "dummy_label";
		$this->viewField->parent     = get_class($this);
        }


	public function setAttributes($attr)
	{
		parent::setAttributes($attr);
		$this->viewField->setAttributes($attr);
	}

	public function getHTML($row,$rowState = 0)
	{
		$html = parent::getHTML($row,$rowState); 
		$this->viewField->label = $this->text;
		$html = preg_replace("/<SPAN CLASS=.text.>.*<.SPAN>/",$this->viewField->getHTML($row),$html);
		return $html;
	}


}


?>
