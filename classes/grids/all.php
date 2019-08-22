<?
$dir = __DIR__ . "/"; 
$dirs = scandir($dir);
foreach($dirs as $file)  
	if($file != "all.php" && substr($file,0,1) != "." && is_file($dir . $file)) 
		require_once($dir . $file);
?>
