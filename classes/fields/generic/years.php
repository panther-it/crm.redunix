<?
require_once __DIR__ . "/dropdownlist.php";
require_once __DIR__ . "/../../sql/sqlstaticvalues.php";

class YearsField extends DropDownList 
{
        function __construct($param1 = NULL)
        {
                parent::__construct($param1);
		$this->datasource = new SqlStaticValues(  "2009=2009"
                                                       . ",2010=2010"
                                                       . ",2011=2011"
                                                       . ",2012=2012"
                                                       . ",2013=2013"
                                                       . ",2014=2014"
                                                       . ",2015=2015"
                                                       . ",2016=2016"
                                                       . ",2017=2017"
                                                       . ",2018=2018"
                                                       . ",2019=2019"
						       );

        }

}


?>
