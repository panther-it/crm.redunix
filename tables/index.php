<? require_once __DIR__ . "/../header.php"; ?>
<? require_once __DIR__ . "/webparts/block.php"; ?>
<? if(empty($_GET["cat"])) $_GET["cat"] = "datacenter"; ?>
	<div id='devide'>
          <a href="/index.php">Home</a> &gt; 
          <a href="/tables/index.php">Tables</a> &gt; 
          <?= ucfirst($_GET["cat"]) ?>
        </div>
        <div id='products'>
        <h1><?= ucfirst($_GET["cat"]) ?></h1> 
            <p class="menuHeader">
              <!-- intro text here -->
            </p>
            <br />
<div class="menuBlock" id="general" style="display:<?= $_GET["cat"] == "general" ? "block" : "none" ?>;">
<?
        //if ($auth->allowed("contacts"     )) { block("contacts"           ,"Contacts"         ); } 
        if ($auth->allowed("customers"    )) { block("customers"          ,"Customers"        ); }  
        if ($auth->allowed("orders"       )) { block("orders"             ,"Orders"           ); } 
        //if ($auth->allowed("organizations")) { block("organizations"      ,"Organizations"    ); }  
        if ($auth->allowed("authorization")) { block("authorizations"     ,"Authorization"    ); } 
?>
</div>
<div class="menuBlock" id="datacenter" style="display:<?= $_GET["cat"] == "datacenter" ? "block" : "none" ?>;">
<?
        if ($auth->allowed("datacenters")      ) { block("datacenters"      ,"DataCenters"      ); }
        if ($auth->allowed("suites")           ) { block("suites"           ,"Suites"           ); }
        if ($auth->allowed("racks")            ) { block("racks"            ,"Racks"            ); }
        if ($auth->allowed("devices")          ) { block("devices"          ,"Devices"          ); }
        if ($auth->allowed("accessdevices")    ) { block("accessdevices"    ,"Access Cards"     ); }
        if ($auth->allowed("coloaccess")       ) { block("coloaccesses"     ,"Colo Access"      ); }
        if ($auth->allowed("cables")           ) { block("cables"           ,"Cable Connections"); }
        if ($auth->allowed("devicemanagement" )) { block("devicemanagements","Device Management"); }
?>
</div>
<div class="menuBlock" id="shared" style="display:<?= $_GET["cat"] == "shared" ? "block" : "none" ?>;">
<?
        if ($auth->allowed("domains")         ) { block("domains"         ,"Domains"          ); }
        if ($auth->allowed("nameservers")     ) { block("nameservers"     ,"Nameservers"      ); }
?>
</div>
<div class="menuBlock" id="office" style="display:<?= $_GET["cat"] == "office" ? "block" : "none" ?>;">
<?
        if ($auth->allowed("tasks")           ) { block("tasks"           ,"Tasks"            ); }
?>
</div>
<div class="menuBlock" id="shop" style="display:<?= $_GET["cat"] == "shop" ? "block" : "none" ?>;">
<?
        if ($auth->allowed("products"     )) { block("products"           ,"Products"         ); } 
?>
</div>
</div>
<!--CRM#include virtual="../webparts/framework_bottom.shtml"  -->
