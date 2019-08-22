<?
require_once __DIR__ . "/../database.php";
require_once __DIR__ . "/../settings.php";
require_once __DIR__ . "/../sql/sqlcustomers.php";

class Customer 
{
        public  $id;
        private $record;

        function __construct($id)
        {
                global $database;
                $this->id     = $id;
                $query        = SqlCustomers::query(Settings::ASFORM,"id=" . $id);
                echo $query . "\n";
                $this->record = $database->fetchObject($query);
                echo "Customer modal "; print_r($this->record);
        }

        public function defaultNameservers($value = NULL)
        {
                if (empty($value))
                {
                        //get value
                        return $this->record->default_nameservers;
                }
                else
                {
                        //set value
                        $values["default_nameservers"] = $value;
                        SqlCustomers::update($values);
                }
        }


}

?>
