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
        $domainname = array_shift($argv);
        $parameters = $argv;
}

switch($action)
{
        case "HELP":
                echo("domain_cli [action] [domainname] [parameters]\n"
                    ."\n"
                    ."Availible actions:\n"
                    ."* HELP   - prints this help\n"
                    ."* VIEW   - shows domain info (_TODO_)\n"
                    ."* WHOIS  - returns whois info in compact format\n"
                    ."* EDIT   - change domain attributes (_TODO_)\n"
                    ."* ADD    - adds a domain into the crm database\n"
                    ."* DELETE - deletes a domain from the crm database\n"
                    ."* DEL    - acronym for DELETE\n"
                    ."\n"
                    ."VIEW Parameters: none\n"
                    ."DEL  Parameters: none\n"
                    ."ADD/EDIT  Parameters: \n"
                    ."* 1: owner-sidn-handle  / owner-crm-contact-id\n"
                    ."* 2: adminc-sidn-handle / adminc-crm-contact-id\n"
                    ."* 3: techc-sidn-handle  / techc-crm-contact-id\n"
                    ."* 4: ns1:ns2[:ns3]      / nameservers-id\n"
                    );
                break;
         case "WHOIS":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/tools/domaintools.php");
                print_r(DomainTools::getNLWhois($domainname));
                break;
         case "ADD":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/settings.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/database.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqldomains.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqlnameservers.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/modals/customer.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/tools/domaintools.php");

                //Parameters
                $values                = Array();
                $values["domainname"]  = $domainname;
                $values["customerid"]  = $parameters[0];
                $customer  = new Customer($values["customerid"]);
                $whoisInfo = DomainTools::getNLWhois($domainname);
                echo "WHOIS Result "; print_r($whoisInfo);
                if ($whoisInfo["status"] == "active")
                {
                        $nsid = SqlNameservers::getIdByNS($whoisInfo["ns1"]); 
                        if ($nsid == -1)
                        {
                                //Add nameservers if not exists
                                $nsvalues["ns1"] = $whoisInfo["ns1"];
                                $nsvalues["ns2"] = $whoisInfo["ns2"];
                                $nsvalues["ns3"] = $whoisInfo["ns3"];
                                SqlNameservers::insert($nsvalues);
                                $nsid = SqlNameservers::getIdByNS($whoisInfo["ns1"]); 
                        }

                        $values["owner"]       = $whoisInfo["owner"];
                        $values["adminc"]      = $whoisInfo["adminc"];
                        $values["techc"]       = $whoisInfo["techc"];
                        $values["nameservers"] = $nsid;

                }
                else
                {
                        $defaultNS = $customer->defaultNameservers();
                        if (count($parameters) >= 2) $values["owner"]       = $parameters[1]; else $values["owner"]       = $values["customerid"];
                        if (count($parameters) >= 3) $values["adminc"]      = $parameters[2]; else $values["adminc"]      = $values["customerid"];
                        if (count($parameters) >= 4) $values["techc"]       = $parameters[3]; else $values["techc"]       = "DOM004043-PANIT";
                        if (count($parameters) >= 5) 
                        {
                                $values["nameservers"] = $parameters[4]; 
                                //save default nameservers if not exists
                                if (empty($defaultNS)) $customer->defaultNameservers($values["nameservers"]);
                        }
                        else
                        {
                                $values["nameservers"] = $defaultNS;
                        }
                }
                
                //Check
                echo "Database Values "; print_r($values); echo "\n";
                if (empty($values["nameservers"]) 
                {
                        echo("ERROR: nameservers empty.\n");
                        exit;
                }

                //Execute
                $status = SqlDomains::insert($values); 
                echo $status . "\n";
                break;
 }

?>
