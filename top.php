<? require_once(__DIR__ . "/webparts/header.php") ?>
  <div id='main'>
    <div id='container'>
    <div id='wide'>
      <div id='menu'>
        <div id='menu_left'> 
		<img src="http://www.redunix.nl/img/logo.jpg" />
        </div>

        <div id='menu_right'>
	<table border="0"><tr><td style="width: 320px;">
          <p style="margin-top: -4px;">
              <a href='/tables/'          target="mainFrame"
                 onClick="document.getElementById('tablesMenu').style.display='block'; document.getElementById('appsMenu').style.display='none'; return false;">Basic Management</a>...
	  </p>
            <p id="tablesMenu" style="display:block; margin-top: -8px; margin-right: 0px;">
              <a href="/tables/index.php?cat=general"      target="mainFrame">Customer</a>
            | <a href="/tables/index.php?cat=datacenter"   target="mainFrame">DataCenter</a>  
            | <a href="/tables/index.php?cat=shared"       target="mainFrame">Hosting</a>  
            | <a href="/tables/index.php?cat=office"       target="mainFrame">Office</a>  
            | <a href="/tables/index.php?cat=shop"         target="mainFrame">Shop</a>  
            </p>
          <p style="margin-top: 5px;">
             <a href="/apps/"            target="mainFrame"
                 onClick="document.getElementById('appsMenu').style.display='block'; document.getElementById('tablesMenu').style.display='none'; return false;">Advanced Management</a>...
          </p>
            <p id="appsMenu" style="display:block; margin-top: -7px; margin-right: 0px;">
              <a href="/apps/index.php?cat=general"      target="mainFrame">General</a>
            | <a href="/apps/index.php?cat=datacenter"   target="mainFrame">DataCenter</a>  
            | <a href="/apps/index.php?cat=shared"       target="mainFrame">Hosting</a>  
            | <a href="/apps/index.php?cat=shop"         target="mainFrame">Shop</a>  
            </p>
	</td><td style="width:120px;">
          <p>
             <a href=""                 target="mainFrame"
                 onClick="parent.frames[1].location.reload(); return false;">Reload</a>     
            | <a href="login.php?logout" target="_top"     >Logout</a>
          </p>
	</td><td style="vertical-align: middle; text-align: right;">
		<?= $auth->contact->firstname . " " . $auth->contact->lastname . " - " . $auth->customer->name ?>
	    <form method="get" action="/apps/search.php" target="mainFrame">
		<input type="hidden" name="Organizations[filter][customer]" value="" /> 
		<input type="hidden" name="Orders[filter][customer]"        value="" /> 
		<input type="hidden" name="Products[filter][owner]"         value="" /> 
		<input type="hidden" name="Contacts[filter][customer]"      value="" /> 
		<input type="hidden" name="Domains[filter][customer]"       value="" /> 
		<input type="hidden" name="Devices[filter][customer]"       value="" /> 
		<input type="text"   name="keyword" style="width: 200px;" /><br/>
		<input type="submit" value="search" style="width: 100px;" />
	    </form>
	</td>
        </tr></table>
      </div>
    </div>
    </div>
  </div>
</body>
</html>
