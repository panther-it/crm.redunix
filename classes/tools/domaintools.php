<?
require_once(__DIR__ . "/../database.php");
require_once(__DIR__ . "/../sql/sqlcustomers.php"); 

class DomainTools 
{

        function __construct() 
        {
        }


        public static function getNLWhois($domainname)
        {
                $domainname = strtolower($domainname);
                $result     = Array();

                //sanity checks
                if (substr($domainname,-3) != ".nl")
                {
                        return "GetSIDNHandlesFromWhois() function only works on .nl ccTLD";
                }

                exec("/usr/bin/whois " . $domainname. "|grep \":\" -A 2",$whoisResult);
                for ($i=0; $i < count($whoisResult); $i++)
                {
                        $line = $whoisResult[$i];
                        switch(substr($line,0,10))
                        {
                                case "   Status:": 
                                        $value = preg_replace("/^[^:]*:/","",$line);
                                        $result["status"] = trim($value);
                                        $i++;
                                        break;
                                case "   Registr":
                                        $line  = $whoisResult[++$i];
                                        $value = trim($line);
                                        if (!isset($result["owner"]))
                                        {
                                                $result["owner"] = $value;
                                                $i+=2;
                                        }
                                        else
                                        {
                                                $result["registrar"] = $value;
                                                $i+=2;
                                        }
                                        break;
                                case "   Adminis":
                                        $line  = $whoisResult[++$i];
                                        $value = trim($line);
                                        $result["adminc"] = $value;
                                        $i+=2;
                                        break;
                                case "   Technic":
                                        $line  = $whoisResult[++$i];
                                        $value = trim($line);
                                        $result["techc"] = $value;
                                        $i+=2;
                                        break;
                                case "   Domain ":
                                        if (!isset($result["name"]))
                                        {
                                                $line  = $whoisResult[++$i];
                                                $value = trim($line);
                                                $result["name"] = $value;
                                        }
                                        else
                                        {
                                                $line  = $whoisResult[++$i];
                                                $value = trim($line);
                                                $result["ns1"] = $value;
                                                $line  = $whoisResult[++$i];
                                                $value = trim($line);
                                                $result["ns2"] = $value;
                                        }
                                        $i++;
                                        break;
                                case "   Date re":
                                        $value = preg_replace("/^[^:]*:/","",$line);
                                        $result["created"] = trim($value);
                                case "   Record ":
                                        $value = preg_replace("/^[^:]*:/","",$line);
                                        $result["updated"] = trim($value);
                        }
               }
               return $result;
        }

}
/*
   Domain name:
      panther-it.nl

   Status: active

   Registrant:
      PAN001702-PANIT
      Panther IT Services
--
   Committed to ADR: yes

   Administrative contact:
      TER003760-PANIT
      S Terburg
--
   Registrar:
      Transip BV
      Schipholweg 9 b
--
   Technical contact(s):
      TER003760-PANIT
      S Terburg
--
   Domain nameservers:
      udns1.ultradns.net    
      udns2.ultradns.net    
--
   Date registered: 27-05-2005
   Record last updated: 17-08-2007

   Record maintained by: NL Domain Registry

   Copyright notice
*/
?>
