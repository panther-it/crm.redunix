<?
/***********************************************
Project:	NOC.GRAFIX.NL API
Type:			Client connection class
File:			client_class.php
Author:		M. van der Poel <martijn@grafix.nl>
CVSv:			1.7
Creation:	24/05/2006
Changed:	31/05/2006
***********************************************/
class GrafixTransaction 
{
	var $host = 'noc.grafix.nl';
	var $port = 80;
	var $user = "PR2182";
	var $pass = "geronimo";

	// Function to add paramater to command
	function AddParam($cmd)
	{
		$this->poststring	= "";
		$md5 = "";

		foreach($cmd as $key => $val)
		{
			$this->poststring	.= $key."=".urlencode( $val )."&";
			$md5 .= $key."=".$val."&";
		}

		// Add user & pass
		$md5 = preg_replace("/\n/i", "", $md5);
		$md5 = preg_replace("/ /"  , "", $md5);
		$this->poststring	.= "id=".$this->user."&pw=".md5($this->pass);
	}

	// Function to make actual transaction
	function DoTransaction()
	{	
		$socket = fsockopen($this->host, $this->port );
		// Opening socket failed
		if(!$socket)
		{
			$data = array(
							'is_success' => false,
							'response_code' => 400,
							'response_text' => 'Unable to establish socket.'
							);
			return $data;
		}else
		{
			// Send GET command with our parameters
			$in = "GET /server.php?" . $this->poststring . " HTTP/1.1\r\nHost: ".$this->host."\r\nConnection: Close\r\n\r\n";
			$out = '';
			fputs($socket, $in);
			// Read response
			while($out = fread($socket,2048))
			{
				$raw .= $out;
			}
			fclose($socket);
			// Parse the output
			$this->ParseResponse($raw);
		}
	}

	// Function to parse the response
	function ParseResponse($buffer)
	{
		$this->values = "";
		$lines = explode("\n", $buffer);
		$numlines = count($lines);

		// Skip past header
		$i = 0;
		while(trim($lines[$i]) != "")
		{
			$i = $i + 1;
		}
		$startline = $i;
		
		// Parse lines
		for($i = $startline; $i < $numlines; $i++)
		{
			// Is this line a comment?
			if(substr($lines[$i], 1, 1 ) != ";")
			{
				// It is not, parse it
				$result = explode("=", $lines[$i]);
 			
				// Make sure we got 2 strings
				if(count($result) >= 2)
				{
					$name	= trim($result[0]);
					$value = trim($result[1]);
					$this->values[$name] = $value;
				}
			}
		}

		// Return values
		if($this->values['RETcode']=="1000")
		{
			$this->values['is_success'] = true;
		}else
		{
			$this->values['is_success'] = false;
		}
	}
}
?>
