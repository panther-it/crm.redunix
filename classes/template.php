<?
class Template 
{
        public $name;
        public $fields = Array();


        function __construct($name)
        {
                session_start();
                $this->name = $name;
        }


        /*
         * Name       : getHTML()
         * Description: builds the HTML Table/Form
         */
        public function getHTML()
        {
                $html = file_get_contents("templates/" . $this->name . ".html");
                foreach($this->fields as $key => $value)
                        $html = str_replace($key,$value,$html);
                return $html;
        }

        public function printHTML()
        {
                print($this->getHTML());
                flush();
        }

        public function mailHTML()
        {
        }
}

?>
