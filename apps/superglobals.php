<? 
require_once __DIR__ . "/../header.php"; 
?>
<H1>$_SESSION</H1><PRE><? var_dump($_SESSION); ?></PRE><HR size="1" /><BR/>
<H1>$_GET    </H1><PRE><? var_dump($_GET    ); ?></PRE><HR size="1" /><BR/>
<H1>$_POST   </H1><PRE><? var_dump($_POST   ); ?></PRE><HR size="1" /><BR/>
<H1>$_REQUEST</H1><PRE><? var_dump($_REQUEST); ?></PRE><HR size="1" /><BR/>
<H1>$_COOKIE </H1><PRE><? var_dump($_COOKIE ); ?></PRE><HR size="1" /><BR/>
<H1>$_SERVER </H1><PRE><? var_dump($_SERVER ); ?></PRE><HR size="1" /><BR/>
<H1>$_ENV    </H1><PRE><? var_dump($_ENV    ); ?></PRE><HR size="1" /><BR/>
<H1>$_FILES  </H1><PRE><? var_dump($_FILES  ); ?></PRE><HR size="1" /><BR/>

<!--CRMinclude virtual="../webparts/framework_bottom.shtml"  -->
