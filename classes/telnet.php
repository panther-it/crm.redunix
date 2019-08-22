<?
//require_once(__DIR__ . "/settings.php");

class Telnet 
{
	private $tcp;

        function __construct($host) 
        {
		//session_start();
		//print("host=$host");
		$this->tcp = fsockopen($host,23);
	}

        public function cmd($cmd)
        {
		fputs($this->tcp,$cmd . "\r");
		sleep(1);
		if (is_resource($this->tcp)) print "is_resource";
		return $this->read(); 
        }

	public function read() 
	{
		$r='';
		do 
		{ 
			$r.=fread($this->tcp,1);
			$s=socket_get_status($this->tcp);
		} 
		while ($s['unread_bytes']);
		return $r;
	}

}
?>
