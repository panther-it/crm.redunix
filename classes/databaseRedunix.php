<?
require_once __DIR__ . "/settings.php";

class DatabaseRedunix extends Database
{

        private $db;

        function __construct() 
        {
                parent::connect(Settings::DB_HOST_REDUNIX, Settings::DB_USER_REDUNIX, Settings::DB_PWD_REDUNIX,Settings::DB_NAME_REDUNIX);
        }
}

$databaseRedunix = new DatabaseRedunix();
?>
