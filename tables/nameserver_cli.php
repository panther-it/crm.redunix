#!/usr/bin/php
<?
if ($argc < 2)
{
        $action = "HELP";
}
else
{
        $progname   = array_shift($argv);
        $action     = array_shift($argv);
        $action     = trim($action," -/");
        $action     = strtoupper($action);
        $parameters = $argv;
}

switch($action)
{
        case "HELP":
                echo("domain_cli [action] [parameters]\n"
                    ."\n"
                    ."Availible actions:\n"
                    ."* HELP   - prints this help\n"
                    ."* VIEW   - shows detailed nameservers info \n"
                    ."* EDIT   - change nameservers (_TODO_)\n"
                    ."* ADD    - adds a nameservers-entry into the crm database (_TODO_)\n"
                    ."* DELETE - deletes a nameserver from the crm database (_TODO_)\n"
                    ."* DEL    - acronym for DELETE\n"
                    ."\n"
                    ."VIEW Parameters: \n"
                    ."* 1: nameserver-part\n"
                    ."\n"
                    ."Add Parameters: \n"
                    ."* 1: ns1\n"
                    ."* 2: ns2\n"
                    ."* 3: ns3\n"
                    ."\n"
                    ."Edit Parameters: \n"
                    ."* 1: id\n"
                    ."* 2: ns1\n"
                    ."* 3: ns2\n"
                    ."* 4: ns3\n"
                    );
                break;
         case "ADD":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqlnameservers.php");
                $values        = Array();
                $values["ns1"] = $parameters[0];
                $values["ns2"] = $parameters[1];
                $values["ns3"] = $parameters[2];
                $status = SqlNameservers::insert($values); 
                echo $status;
                break;
         case "EDIT":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqlnameservers.php");
                $values        = Array();
                $values["id"]  = $parameters[0];
                $values["ns1"] = $parameters[1];
                $values["ns2"] = $parameters[2];
                $values["ns3"] = $parameters[3];
                $status = SqlNameservers::update($values); 
                echo $status;
                break;
         case "VIEW":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/settings.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/database.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqlnameservers.php");
                $searchstr = $parameters[0];
                if (!isset($searchstr)) $searchstr = "";
                $gridquery = SqlNameservers::query(Settings::ASGRID, "   ns1 like '%" . $searchstr . "%' "
                                                                   . "OR ns2 like '%" . $searchstr . "%' "
                                                                   . "OR ns3 like '%" . $searchstr . "%' "
                                                                   );
                $gridrs    = $database->query($gridquery);
                if (!is_resource($gridrs)) 
                {
                        echo $gridquery . "\n\n";
                        echo $gridrs . "\n";
                        exit;
                }
                if (mysql_num_rows($gridrs) == 0)
                {
                        echo $gridquery . "\n\n";
                        echo "No matching records found.\n";
                        exit;
                }
                while($r = mysql_fetch_object($gridrs))
                {
                        echo(str_pad($r->id  , 4) . "| "
                            .str_pad($r->ns1 , 40) . "| "
                            .str_pad($r->ns2 , 40) . "| "
                            .str_pad($r->ns3 , 40)     
                            ."\n");
                        //which record to display best
                        if (strtolower($r->ns1) == strtolower($searchstr)      )  $formNS1 = $r->id;
                        if (stripos($r->ns1,$searchstr) >= 0 && !isset($formNS1)) $formNS1 = $r->id;
                        if (strtolower($r->ns2) == strtolower($searchstr)      )  $formNS2 = $r->id;
                        if (stripos($r->ns2,$searchstr) >= 0 && !isset($formNS2)) $formNS2 = $r->id;
                        if (strtolower($r->ns3) == strtolower($searchstr)      )  $formNS3 = $r->id;
                        if (stripos($r->ns3,$searchstr) >= 0 && !isset($formNS3)) $formNS3 = $r->id;
                }
                //which record to display best
                     if (isset($formNS1)) $formId = $formNS1;
                else if (isset($formNS2)) $formId = $formNS2;
                else if (isset($formNS3)) $formId = $formNS3;
                else $formId = -1;
                
                if (!empty($searchstr))
                {
                        $formquery = SqlNameservers::query(Settings::ASFORM,"id=" . $formId);
                        $formrs    = $database->query($formquery);
                        $formr     = mysql_fetch_object($formrs);
                        echo "\n";
                        print_r($formr);
                }
                break;
}

?>
