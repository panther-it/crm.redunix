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
                    ."* VIEW   - shows detailed contact info \n"
                    ."* EDIT   - change domain attributes (_TODO_)\n"
                    ."* ADD    - adds a domain into the crm database (_TODO_)\n"
                    ."* DELETE - deletes a domain from the crm database (_TODO_)\n"
                    ."* DEL    - acronym for DELETE\n"
                    ."\n"
                    ."VIEW Parameters: \n"
                    ."* 1: company/firstname/lastname/email\n"
                    ."\n"
                    ."ADD Parameters: \n"
                    ."* 1: customerid\n"
                    ."* 2: company\n"
                    ."* 3: name\n"
                    ."* 4: email\n"
                    ."* 5: phone\n"
                    ."* 6: address\n"
                    ."* 7: sidn owner handle\n"
                    ."* 8: sidn adminc handle\n"
                    );
                break;
         case "ADD":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqlcontacts.php");
                $values               = Array();
                $values["customerid"] = $parameters[0];
                $values["company"   ] = $parameters[1];
                $values["name"]       = $parameters[2];
                $values["email"]      = $parameters[3];
                $values["phone"]      = $parameters[4];
                $values["address"]    = $parameters[5];
                $values["sidn_owner"] = $parameters[6];
                $values["sidn_adminc"]= $parameters[7];
                $status = SqlContacts::insert($values); 
                echo $status;
                break;
          case "VIEW":
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/settings.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/database.php");
                require_once("/var/www/panther/crm.redunix.nl/html/../classes/sql/sqlcontacts.php");
                $searchstr = $parameters[0];
                $gridquery = SqlContacts::query(Settings::ASGRID, "   company like '%" . $searchstr . "%' "
                                                                . "OR name    like '%" . $searchstr . "%' "
                                                                . "OR email   like '%" . $searchstr . "%' "
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
                        echo(str_pad($r->id       , 15) . "| "
                            .str_pad($r->company  , 40) . "| "
                            .str_pad($r->name     , 40) . "| "
                            .str_pad($r->email    , 40)     
                            ."\n");
                        //which record to display best
                        if (strtolower($r->company) == strtolower($searchstr)           ) $formCompany = $r->id;
                        if (stripos($r->company,$searchstr) >= 0 && !isset($formCompany)) $formCompany = $r->id;
                        if (strtolower($r->name)    == strtolower($searchstr)           ) $formName    = $r->id;
                        if (stripos($r->name,$searchstr)    >= 0 && !isset($formName)   ) $formName    = $r->id;
                        if (strtolower($r->email)   == strtolower($searchstr)           ) $formEmail   = $r->id;
                        if (stripos($r->email,$searchstr)   >= 0 && !isset($formEmail)  ) $formEmail   = $r->id;
                }
                //which record to display best
                     if (isset($formCompany)) $formId = $formCompany;
                else if (isset($formName))    $formId = $formName;
                else if (isset($formEmail))   $formId = $formEmail;
                else $formId = -1;

                $formquery = SqlContacts::query(Settings::ASFORM,"id=" . $formId);
                $formrs    = $database->query($formquery);
                $formr     = mysql_fetch_object($formrs);
                echo "\n";
                print_r($formr);
                break;
}

?>
