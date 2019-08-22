<?
require_once __DIR__ . "/../settings.php";

class SqlStaticValues
{
	public $values;

        function __construct($param1 = NULL)
        {
		if ($param1 != NULL)
			$this->values = $param1; 

        }


        public function query($viewType="",$constraints = "")
        {
		if (is_string($this->values))
		{	//first split string into array
			$values = explode(",",$this->values);
			for ($i=0; $i < count($values); $i++)
			{
				$values[$i] = explode("=",$values[$i]);
				$values[$i][0] = trim($values[$i][0]);
				if (count($values[$i]) > 1) 
					$values[$i][1] = trim($values[$i][1]);
			} 
			$this->values = $values; //save for caching
		}
		return $this->values;
        }


	public function get($constraint)
	{
		if (strpos($constraint,"=") !== FALSE)
			$constraint = trim(strstr($constraint,"=")," =");

		$values = $this->query();
		foreach($values as $value)
		{
			if ($value[0] == $constraint               ) return $value[1];
			if (stripos($value[1],$constraint) === TRUE) return $value[0];
		}
		
		return "";
	}


        public function insert($values)
        {
        }


        public function update($values)
        {
        }


        public function delete($values)
        {
        }
}

?>
