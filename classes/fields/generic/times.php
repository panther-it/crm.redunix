<?
require_once __DIR__ . "/hours.php";
require_once __DIR__ . "/minutes.php";
require_once __DIR__ . "/generic.php";

class TimesField extends GenericField 
{
	public $hours; 
	public $minutes;


        function __construct($param1 = null)
        {
		$this->hours   = new HoursField($param1);
		$this->minutes = new MinutesField($param1);
        }


        public function getHTML($row, $rowState = 0)
	{
		parent::getHTML($row);
		return $this->hours->getHTML($row,$rowState)
                     . ":"
                     . $this->minutes->getHTML($row,$rowState);
	}



	public function setAttributes($attr)
	{
		parent::setAttributes($attr);
		//unset($attr["name"]);
		$this->hours->setAttributes($attr);
		$this->minutes->setAttributes($attr);
	}


	public function __get($varname)
	{
		switch(strtolower($varname))
		{
			case "name":
				return $this->name;
			default:
				return $this->$varname;
		}
	}

	public function __set($varname,$value)
	{
		switch(strtolower($varname))
		{
			case "name":
				$this->name    = $value;
				$minutes->name = $value . "[minutes]";
				$hours->name   = $value . "[hours]" ;
				break;
			default:
				$minutes->$varname = $value;
				$hours->$varname   = $value;
		}
	}

 

}


?>
