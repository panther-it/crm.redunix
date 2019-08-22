<?
require_once __DIR__ . "/dropdownlist.php";
require_once __DIR__ . "/../../sql/sqlstaticvalues.php";

class MonthsField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues(  "01=01"
                                                       . ",02=02"
                                                       . ",03=03"
                                                       . ",04=04"
                                                       . ",05=05"
                                                       . ",06=06"
                                                       . ",07=07"
                                                       . ",08=08"
                                                       . ",09=09"
                                                       . ",10=10"
                                                       . ",11=11"
                                                       . ",12=12"
						       );

        }

}


?>
