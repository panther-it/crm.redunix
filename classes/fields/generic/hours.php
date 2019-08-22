<?
require_once __DIR__ . "/dropdownlist.php";
require_once __DIR__ . "/../../sql/sqlstaticvalues.php";

class hoursField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues(  "0=00"
                                                       . ",1=01"
                                                       . ",1=02"
                                                       . ",1=03"
                                                       . ",1=04"
                                                       . ",1=05"
                                                       . ",1=06"
                                                       . ",1=07"
                                                       . ",1=08"
                                                       . ",1=09"
                                                       . ",10=10"
                                                       . ",11=11"
                                                       . ",12=12"
                                                       . ",13=13"
                                                       . ",14=14"
                                                       . ",15=15"
                                                       . ",16=16"
                                                       . ",17=17"
                                                       . ",18=18"
                                                       . ",19=19"
                                                       . ",20=20"
                                                       . ",20=20"
                                                       . ",21=21"
                                                       . ",22=22"
                                                       . ",23=23"
						       );

        }

}


?>
