<?
$dir = __DIR__ . "/"; 
$js_includes = array_unique($js_includes);

foreach($js_includes as $file)  
{
	if (!preg_match("/\//",$file)) $file = "/lib/" . $file;
	print("<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"" . $file . "\"    TYPE=\"text/javascript\"></SCRIPT>\n");
}
?>
