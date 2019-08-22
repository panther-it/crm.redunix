<?
require_once __DIR__ . "/years.php";
require_once __DIR__ . "/months.php";
require_once __DIR__ . "/days.php";
require_once __DIR__ . "/generic.php";

class DatesField extends GenericField 
{
	public $year; 
	public $month;
	public $day;


        function __construct($param1 = null)
        {
		$this->year  = new YearsField($param1);
		$this->month = new MonthsField($param1);
		$this->day   = new DaysField($param1);
        }

        public function getHTML($row, $rowState = 0)
	{
		parent::getHTML($row);
		return $this->year->getHTML($row,$rowState)
                     . "-"
                     . $this->month->getHTML($row,$rowState)
                     . "-"
                     . $this->day->getHTML($row,$rowState);

	}


	public function setAttributes($attr)
	{
		parent::setAttributes($attr);
		//if (!empty($attr->name) $attr->name .= "year";
		$this->year->setAttributes($attr);
		$this->month->setAttributes($attr);
		$this->day->setAttributes($attr);
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
				$this->name  = $value;
				$day->name   = $value . "[day]"  ;
				$month->name = $value . "[month]";
				$year->name  = $value . "[year]" ;
				break;
			default:
				$day->$varname   = $value;
				$month->$varname = $value;
				$year->$varname  = $value;
		}
	}

 

}


?>
