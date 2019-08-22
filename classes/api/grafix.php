<?php

/**
 * A class for interacting with the GrafiX NOC
 *
 * @projectDescription NOC API
 * @author GrafiX Internet B.V.
 * @version 1.1
 */

class GrafixTransaction {
	var $host = 'api.grafix.nl';
	var $port = 80;
	var $username = "redunix";
	var $password = "geronimo";
	public $values   = "";
	public $rawResponse = "";


        function __construct($param1 = NULL) 
        {
		if (!empty($param1)) 
		{
			$this->AddParam($param1);
			$this->DoTransaction();
		}
        }



        # Set the NOC username
        function username($username) {
                $this->username = $username;
        }


        # Set the NOC password
        function password($password) {
                $this->password = $password;
        }


        # Function to add paramater to command
        function AddParam($cmd) {
                $this->poststring       = "";
                $md5 = "";

                foreach($cmd as $key => $val) {
                        $this->poststring .= $key."=".urlencode($val) . "&";
                        $md5 .= $key."=".$val."&";
                }

                # Add user & pass
                $md5 = eregi_replace("\n", "", $md5);
                $md5 = eregi_replace(" ", "", $md5);
                $this->poststring .= "id=" . $this->username . "&pw=" . md5($this->password);
        }


        # Function to make actual transaction
        function DoTransaction() {
                $socket = fsockopen($this->host, $this->port );

                # Opening socket failed
                if(!$socket) {
                        $data = array(
                                'is_success' => false,
                                'response_code' => 400,
                                'response_text' => 'Unable to establish socket.'
                        );
			$this->values = $data;
                        return $data;
                } else {
                        # Send GET command with our parameters
                        $in = "GET /server.php?" . $this->poststring . " HTTP/1.1\r\nHost: " . $this->host . "\r\nConnection: Close\r\n\r\n";

                        fputs($socket, $in);

                        # Read response
                        $out = "";
                        $raw = "";
                        while($out = fread($socket,2048)) {
                                $raw .= $out;
                        }

                        fclose($socket);

                        # Parse the output
                        $this->ParseResponse($raw);
                }
        }


        # Function to parse the response
        function ParseResponse($buffer) {
		$this->rawResponse = $buffer;
                $this->values = "";
                $lines = explode("\n", $buffer);
                $numlines = count($lines);

                # Skip past header
                $i = 0;
                while(trim($lines[$i]) != "")
                {
                        $i = $i + 1;
                }
                $startline = $i;

                # Parse lines
                for($i = $startline; $i < $numlines; $i++) {
                        # Is this line a comment?
                        if(substr($lines[$i], 1, 1 ) != ";") {
                                // It is not, parse it
                                $result = explode("=", $lines[$i]);

                                # Make sure we got 2 strings
                                if(count($result) >= 2) {
                                        $name   = trim($result[0]);
                                        $value = trim($result[1]);
                                        $this->values[$name] = $value;
                                }
                        }
                }

                # Return values
                if($this->values['RETcode']=="1000") {
                        $this->values['is_success'] = true;
                } else {
                        $this->values['is_success'] = false;
                }
        }
}
?>
