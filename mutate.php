<?
header("Content-Type: text/plain; charset=ISO-8859-1");
//header("Connection: close");
require_once(__DIR__ . "/classes/authorization.php");

$class  = isset($_POST["class"] ) ? $_POST["class" ] : $_GET["class" ];
$action = isset($_POST["action"]) ? $_POST["action"] : $_GET["action"];
$action = strtolower($action);
$value  = isset($_POST["value"] ) ? $_POST["value" ] : addslashes($_GET["value" ]);
$values = isset($_POST["values"]) ? $_POST["values"] : $_GET["values"];
$status = "Unknown class \"$class\"  and unknown action \"$action\"";

@print_r($values);
/*
@error_log(@var_export(@http_get_request_headers(),true));
@error_log(@var_export(@headers_list(),true));
@error_log(@var_export(@session_get_cookie_params(),true) . "; " . session_name() . "=" . session_id());
@error_log(@var_export($_COOKIE,true));
@error_log(@var_export($_SESSION,true));
*/
/*
error_log("Mutate: ");
@error_log(@mb_http_input("P"));
@error_log(@var_export(@http_get_request_body(),true));
*/

switch($class)
{
        case "DeviceManagements":
        case "DeviceManagement":
                require_once(__DIR__ . "/classes/sql/sqldevicemanagement.php");
                require_once(__DIR__ . "/classes/apc.php");
                switch($action)
                {
                        case "insert": $status  = SqlDeviceManagement::insert($values); break;
                        case "update": $status  = SqlDeviceManagement::update($values); break;
                        case "delete": $status  = SqlDeviceManagement::delete($values); break;
                        case "test"  : $status  = Power::test($values); break; 
                        default      : $status  = "Unknown action \"$action\"";
                }
                break;
        case "IPMI Power":
                require_once(__DIR__ . "/classes/ipmi.php");
                switch(strtolower($action))
                {
                        case "off"   : $status  = Power::shutdown($values); break;
                        case "on"    : $status  = Power::poweron($values) ; break;
                        case "reboot": $status  = Power::reboot($values)  ; break;
                        case "status": $status  = Power::status($values)  ; break;
                        default      : $status  = "Unknown action \"$action\"";
                }
                break;
         case "APC Power":
                require_once(__DIR__ . "/classes/apc.php");
                switch(strtolower($action))
                {
                        case "off"   : $status  = Power::shutdown($values); break;
                        case "on"    : $status  = Power::poweron($values) ; break;
                        case "reboot": $status  = Power::reboot($values)  ; break;
                        case "status": $status  = Power::status($values)  ; break;
                        default      : $status  = "Unknown action \"$action\"";
                }
                break;
        case "Authorizations":
        case "Authorization":
                require_once(__DIR__ . "/classes/sql/sqlauthorization.php");
                switch($action)
                {
                        case "insert": $status  = SqlAuthorization::insert($values); break;
                        case "update": $status  = SqlAuthorization::update($values); break;
                        case "delete": $status  = SqlAuthorization::delete($values); break;
                        default      : $status  = "Unknown action \"$action\"";
                }
                break;
        case "ColoAccesses":
        case "ColoAccess":
                require_once(__DIR__ . "/classes/sql/sqlcoloaccess.php");
                require_once(__DIR__ . "/classes/sql/sqlcoloaccess.php");
                require_once(__DIR__ . "/classes/sql/sqlauthorization.php");
                require_once(__DIR__ . "/classes/grafix.php");
		$auth    = Array("customer" => $values["customer"]
                                ,"contact"  => $values["contact"]
                                ,"section"  => "coloaccess"
                                ,"level"    => "1");
                switch($action)
                {
                        case "insert": $status  = SqlColoAccess::insert($values); 
                                       $status .= SqlAuthorization::insert($auth);
                                       $status .= Grafix::CreateContact($values["contact"]); break; //TODO: if dc=grafix inbouwen; verplaatsen naar sqlcoloaccess::insert
                        case "update": $status  = SqlColoAccess::update($values); 
                                       $status .= SqlAuthorization::update($auth); break;
                        case "delete": $status  = SqlColoAccess::delete($values); 
                                       $status .= SqlAuthorization::delete($auth); //TODO: wat als meerdere colo_accesses? delete alleen bij laatste
				       $status .= Grafix::DeleteContact($values["contact"]); break;
                        default      : $status  = "Unknown action \"$action\"";
                }
                break;
        case "Cables":
        case "Cable":
                require_once(__DIR__ . "/classes/sql/sqlcables.php");
                require_once(__DIR__ . "/classes/sql/sqldevices.php");
                require_once(__DIR__ . "/classes/sql/sqlcustomers.php");
                require_once(__DIR__ . "/classes/sql/sqlcacti.php");
                require_once(__DIR__ . "/classes/apc.php");
                require_once(__DIR__ . "/classes/cisco.php");
       		//$cable  = $database->getObject("cables",$values);
		if ($deviceA->type == "powerswitch" || $deviceA->type == "switch") { $sw = "A"; $srv = "B"; } else { $sw = "B"; $srv = "A"; }; 
		$server   = SqlDevices::find("id=" . $values["device" . $srv . "_id"]);
		$switch   = SqlDevices::find("id=" . $values["device" . $sw  . "_id"]);
		$switch->port = $values["device" . $sw . "_port"];
		$contacts = $database->query(SqlContacts::query(Settings::ASFORM,"owner=" . $server->customer)); 
	        switch($action)
                {
                        case "insert": $status = SqlCables::insert($values); break;
                        case "update": $status = SqlCables::update($values); break;
                        case "delete": $status = SqlCables::delete($values); 
				       $server = Array("label" => "FREE"
                                                      ,"name"  => "FREE");
				       //$status .= SqlCacti::deleteUser($customer);
				       break;
                         default      : $status = "Unknown action \"$action\"";
                }
		if ($switch->type == "powerswitch" && stripos($switch->brand,"APC"  ) !== FALSE) 
		{
			$status .= Power::setLabel($switch,$server) . "\n";
			while($contact = mysql_fetch_object($contacts))
				$status .= Power::addUser($switch,$contact) . "\n";
		}
		if ($switch->type == "switch"      && stripos($switch->brand,"Cisco") !== FALSE) 
		{
			$status .= Cisco::setLabel($switch,$server) . "\n";
			$status .= SqlCacti::updateInterface($switch,$server) . "\n";
			while($contact = mysql_fetch_object($contacts))
			{
				$status .= SqlCacti::insertUser($contact) . "\n";
				$status .= SqlCacti::insertPermission($contact,$switch) . "\n";
			}
		}
	        break;
        case "AccessDevices":
        case "AccessDevice":
                require_once(__DIR__ . "/classes/sql/sqlaccessdevices.php");
                switch($action)
                {
                        case "insert": $status = SqlAccessDevices::insert($values); break;
                        case "update": $status = SqlAccessDevices::update($values); break;
                        case "delete": $status = SqlAccessDevices::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Customer":
        case "Customers":
                require_once(__DIR__ . "/classes/sql/sqlcustomers.php");
                switch($action)
                {
                        case "insert": $status = SqlCustomers::insert($values); break;
                        case "update": $status = SqlCustomers::update($values); break;
                        case "delete": $status = SqlCustomers::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Contact":
        case "Contacts":
                require_once(__DIR__ . "/classes/sql/sqlcontacts.php");
                require_once(__DIR__ . "/classes/fields/generic/dropdownlist.php");
                switch($action)
                {
                        case "query" : $status = DropDownList::updateValues($values,SqlContacts::query(Settings::ASLIST,"c.owner='" . $value . "'")); break;
                        case "insert": $status = SqlContacts::insert($values); break;
                        case "update": $status = SqlContacts::update($values); break;
                        case "delete": $status = SqlContacts::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Organization":
        case "Organizations":
                require_once(__DIR__ . "/classes/sql/sqlorganizations.php");
                require_once(__DIR__ . "/classes/fields/generic/dropdownlist.php");
                switch($action)
                {
                        case "query" : $status = DropDownList::updateValues($values,SqlOrganizations::query(Settings::ASLIST,"owner='" . $value . "'")); break;
                        case "insert": $status = SqlOrganizations::insert($values); break;
                        case "update": $status = SqlOrganizations::update($values); break;
                        case "delete": $status = SqlOrganizations::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Device":
        case "Devices":
                require_once(__DIR__ . "/classes/sql/sqldevices.php");
                switch($action)
                {
                        case "query" : $status = DropDownList::updateValues($values,SqlDevices::query(Settings::ASLIST,"rack='" . $value . "'")); break;
                        case "insert": $status = SqlDevices::insert($values); break;
                        case "update": $status = SqlDevices::update($values); break;
                        case "delete": $status = SqlDevices::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Task":
        case "Tasks":
        case "SubTasks":
                require_once(__DIR__ . "/classes/sql/sqltasks.php");
                switch($action)
                {
                        case "insert": $status = SqlTasks::insert($values); break;
                        case "update": $status = SqlTasks::update($values); break;
                        case "delete": $status = SqlTasks::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
         case "Domain":
        case "Domains":
                require_once(__DIR__ . "/classes/sql/sqldomains.php");
                require_once(__DIR__ . "/classes/domaincontroller.php");
                switch($action)
                {
                        case "insert": if ($status = DomainController::create($values)) $status = SqlDomains::insert($values); break;
                        case "update": if ($status = DomainController::update($values)) $status = SqlDomains::update($values); break;
                        case "delete": if ($status = DomainController::cancel($values)) $status = SqlDomains::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Nameserver":
        case "Nameservers":
        case "NameServer":
        case "NameServers":
                require_once(__DIR__ . "/classes/sql/sqlnameservers.php");
                require_once(__DIR__ . "/classes/fields/generic/dropdownlist.php");
                switch($action)
                {
                        case "query" : $status = DropDownList::updateValues($values,SqlNameservers::query(Settings::ASLIST,"customer='" . $value . "'")); break;
                        case "insert": $status = SqlNameservers::insert($values); break;
                        case "update": $status = SqlNameservers::update($values); break;
                        case "delete": $status = SqlNameservers::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "ProductGroups":
        case "ProductGroup":
        case "ProductFeatures":
        case "FeatureValue":
        case "FeatureValues":
        case "ProductFeature":
        case "Product":
        case "Products":
                require_once(__DIR__ . "/classes/sql/sqlproducts.php");
                require_once(__DIR__ . "/classes/fields/generic/dropdownlist.php");
	        if (!isset($values["type"]) && $class == "ProductGroups"     ) $values["type"] = "GROUP"  ;
	        if (!isset($values["type"]) && $class == "Products"          ) $values["type"] = "PRODUCT";
	        if (!isset($values["type"]) && $class == "ProductFeatures"   ) $values["type"] = "FEATURE";
	        if (!isset($values["type"]) && $class == "ProductFeatureLink") $values["type"] = "FEATURE";
	        if (!isset($values["type"]) && $class == "FeatureValues"     ) $values["type"] = "VALUE"  ;
                switch($action)
                {
                        case "query" : $status = DropDownList::updateValues($values,SqlProducts::query(Settings::ASLIST,"type='" . $value . "'")); break;
                        case "insert": $status = SqlProducts::insert($values); break;
                        case "update": $status = SqlProducts::update($values); break;
                        case "delete": $status = SqlProducts::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "ProductFeatureLink":
                require_once(__DIR__ . "/classes/sql/sqlproducts.php");
	        if (!isset($values["type"]) && $class == "ProductFeatureLink") $values["type"] = "FEATURE";
                switch($action)
                {
                        case "insert": $status = SqlProducts::insertProductsProducts($values); break;
                        case "update": $status = SqlProducts::updateProductsProducts($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
         case "SubOrder":
        case "SubOrders":
        case "Order":
        case "Orders":
                require_once(__DIR__ . "/classes/sql/sqlorders.php");
                switch($action)
                {
                        case "insert": $status = SqlOrders::insert($values); break;
                        case "update": $status = SqlOrders::update($values); break;
                        case "delete": $status = SqlOrders::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Suite":
        case "Suites":
                require_once(__DIR__ . "/classes/sql/sqlsuites.php");
                switch($action)
                {
                        case "insert": $status = SqlSuites::insert($values); break;
                        case "update": $status = SqlSuites::update($values); break;
                        case "delete": $status = SqlSuites::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "DataCenter":
        case "DataCenters":
                require_once(__DIR__ . "/classes/sql/sqldatacenters.php");
                switch($action)
                {
                        case "insert": $status = SqlDataCenters::insert($values); break;
                        case "update": $status = SqlDataCenters::update($values); break;
                        case "delete": $status = SqlDataCenters::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Rack":
        case "Racks":
                require_once(__DIR__ . "/classes/sql/sqlracks.php");
                switch($action)
                {
                        case "insert": $status = SqlRacks::insert($values); break;
                        case "update": $status = SqlRacks::update($values); break;
                        case "delete": $status = SqlRacks::delete($values); break;
                        default      : $status = "Unknown action \"$action\"";
                }
                break;
        case "Field":
        case "DropDownListField":
                require_once(__DIR__ . "/classes/fields/generic/dropdownlist.php");
                switch($action)
                {
                        case "query": $status = DropDownList::getValues($values); break;
                        default     : $status = "Unknown action \"$action\"";
                }
                break;
        default:
                $status = "Unknown class \"$class\"";
}

echo "\n";
print($status);

?>
