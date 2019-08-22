<?
require_once __DIR__ . "/settings.php";

class DatabaseCacti extends Database
{

        function __construct() 
        {
                parent::connect(Settings::DB_HOST_Cacti, Settings::DB_USER_Cacti, Settings::DB_PWD_Cacti,Settings::DB_NAME_Cacti);
        }

}

$databaseCacti = new DatabaseCacti();
?>
