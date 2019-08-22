<? require_once __DIR__ . "/../header.php"; ?>
<? require_once(__DIR__ . "/../tables/webparts/block.php")              ?>
		<div id='devide'>
          <a href="/index.php">Home</a> &gt; 
          <a href="/apps/index.php">Applications</a> &gt; 
          <?= ucfirst($_GET["cat"]) ?>
        </div>
        <div id='products'>
        <h1><?= ucfirst($_GET["cat"]) ?></h1>
            <br />
<div class="menuBlock" id="general" style="display:<?= $_GET["cat"] == "general" ? "block" : "none" ?>;">
<?
	block("login.php?logout","Logout","login");
	block("phpinfo.php","PHPInfo","phpinfo");
	block("superglobals.php?param1=value1&param2=value1","PHP SuperGlobals","phpinfo");
        block("unit4Export"        ,"Unit4 Export"      );  
?>
</div>
<div class="menuBlock" id="datacenter" style="display:<?= $_GET["cat"] == "datacenter" ? "block" : "none" ?>;">
<?
        //if ($auth->allowed("coloaccess")) { block("grafix"             ,"Grafix"            ); } 
        if ($auth->allowed("coloaccess")) { block("coloaccess"         ,"DataCenter toegang"); }
        if ($auth->allowed("power"      ))   { block("power"              ,"Power Management"  ); }
        //if ($auth->allowed("stats_colo" ))   { block("http://dataverkeer.redunix.nl/index","Server Statistieken","dataverkeer" ); }
        //if ($auth->allowed("monitoring" ))   { block("http://monitoring.redunix.nl/index" ,"Server Monitoring"  ,"monitoring"  ); } 
        //if ($auth->allowed("backup"     ))   { block("http://backup-gfx.redunix.nl/index" ,"Backups (gfx)"      ,"backup-gfx"  ); }
        //if ($auth->allowed("backup"     ))   { block("http://backup-sk.redunix.nl/index"  ,"Backups (sk)"       ,"backup-sk"   ); }
        //if ($auth->allowed("network"    ))   { block("cisco"                              ,"Cisco Commands"     ,"cisco"       ); }
?>
</div>
<div class="menuBlock" id="shared" style="display:<?= $_GET["cat"] == "shared" ? "block" : "none" ?>;">
<?
        if ($auth->allowed("domains"    ))   { block("domreg","Domain Registration"); }
?>
</div>
<div class="menuBlock" id="shop" style="display:<?= $_GET["cat"] == "shop" ? "block" : "none" ?>;">
<?
        block("webstore/index","WebShop","webstore");
?>
</div>
<!--CRM#include virtual="../webparts/framework_bottom.shtml"  -->
